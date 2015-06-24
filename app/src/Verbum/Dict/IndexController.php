<?php

namespace Verbum\Dict;

use Elastica\Client;
use Elastica\Query;
use Elastica\Search;
use Verbum\Core\App;
use Verbum\Core\Response;

class IndexController
{
    /**
     * @var Client elasticsearch client
     */
    protected $elastic;

    /**
     * @var App
     */
    protected $app;

    /**
     * @param Client $elastic
     * @inject elastic
     */
    public function setElastic(Client $elastic)
    {
        $this->elastic = $elastic;
    }

    /**
     * @param App $app
     * @inject app
     */
    public function setApp(App $app)
    {
        $this->app = $app;
    }

    /**
     * Setts main page to response
     *
     * @param Response $r
     */
    public function indexAction(Response $r)
    {
        $template = new MainTemplate();
        $r->setContent($template->render());
        $r->setHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Prepares instance of Search class
     *
     * @param $query
     * @return Search
     */
    protected function prepareSearch($query)
    {
        $search = new Search($this->elastic);
        $search->addIndex('verbum');
        $search->setQuery($query);

        return $search;
    }

    /**
     * Searches elastic and returns data that was found
     *
     * @param string $q query string
     * @return array
     */
    public function searchAction($q)
    {
        $bool = new Query\Bool();
        foreach ($this->app->config['dictionaries'] as $meta) {
            foreach ($meta['search_fields'] as $fieldName => $fieldMeta) {
                $fieldQuery = new Query\Match();
                $fieldQuery->setFieldQuery($fieldName, $q);
                $fieldQuery->setFieldBoost($fieldName, $fieldMeta['boost']);
                $bool->addShould($fieldQuery);
            }
        }

        $query = new Query($bool);
        $search = $this->prepareSearch($query);
        $resultSet = $search->search();
        $data = [];
        foreach ($resultSet->getResults() as $result) {
            $article = $result->getData();
            $article['meta'] = str_replace(' | ', ', ', $article['meta']);
            $data[] = $article;
        }

        return ['result' => $data];
    }

    /**
     * Asks elastic for typeahead suggestions and returns result
     *
     * @param $q query string
     * @return array
     */
    public function typeaheadAction($q)
    {
        $bool = new Query\Bool();
        foreach ($this->app->config['dictionaries'] as $meta) {
            foreach ($meta['typeahead_fields'] as $fieldName => $fieldMeta) {
                $fieldQuery = new Query\Match();
                $fieldQuery->setFieldQuery($fieldName, $q);
                $fieldQuery->setFieldBoost($fieldName, $fieldMeta['boost']);
                $bool->addShould($fieldQuery);
            }
        }

        $query = new Query($bool);
        $search = $this->prepareSearch($query);
        $search->setOption('size', 5);
        $resultSet = $search->search();
        $data = [];
        foreach ($resultSet->getResults() as $result) {
            $data[] = $result->getData();
        }

        return ['result' => $data];
    }
}
