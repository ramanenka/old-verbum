<?php


namespace Verbum\Console;

use Elastica\Client;
use Elastica\Document;
use Elastica\Type\Mapping;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Verbum\Core\App;

class IndexCommand extends Command
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Client
     */
    protected $elastic;

    /**
     * @var string
     */
    protected $fileName = 'rv-blr.xml';

    /**
     * @var int
     */
    protected $threshold = 1000;

    protected function configure()
    {
        $this->setName('index')
            ->setDescription('Performs indexation of all dictionaries');
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
     * @param Client $elastic
     * @inject elastic
     */
    public function setElastic(Client $elastic)
    {
        $this->elastic = $elastic;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param int $threshold
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexConfig = $this->app->config['elastic']['index']['settings'];

        $index = $this->elastic->getIndex('verbum');
        $index->create($indexConfig, true);

        $output->writeln('<info>Starting re-indexation</info>');
        foreach ($this->app->config['dictionaries'] as $key => $meta) {
            $output->writeln("<info>Index $key was created</info>");

            $type = $index->getType($key);

            $mapping = new Mapping($type);
            $mapping->setProperties($meta['mapping']);
            $mapping->send();

            $xml = new \XMLReader();
            $xml->open($this->fileName);
            $docs = [];
            $count = 0;
            while ($xml->read()) {
                if ($xml->name == 'item') {
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $title = $xml->value;
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $meta = $xml->value;
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $definition = $xml->value;
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $xml->read();
                    $source = $xml->value;

                    while ($xml->name != 'item') {
                        $xml->read();
                    }

                    $docs[] = new Document('', [
                        'title' => $title,
                        'meta' => $meta,
                        'definition' => $definition,
                        'source' => $source,
                    ]);

                    if (count($docs) > $this->threshold) {
                        $type->addDocuments($docs);
                        $index->refresh();
                        $count += $this->threshold;
                        $output->writeln("<info>{$this->threshold} docs sent to elastic, $count overall</info>");
                        $docs = [];
                    }
                }
            }

            if ($docs) {
                $type->addDocuments($docs);
                $index->refresh();
                $count += count($docs);
                $output->writeln("<info>".count($docs)." docs sent to elastic, $count overall</info>");
            }

            $output->writeln('<info>DONE</info>');
        }
    }
}
