#!/usr/bin/env bash

set -o xtrace

host=localhost:9200

if [ "$1" = "reimport" ]; then

    curl -XDELETE $host/vt?pretty=true

    curl -XPOST $host/vt?pretty=true -d '{
        settings: {
            number_of_shards: 1,
            number_of_replicas: 0,
            analysis: {
                analyzer: {
                    titles_analyzer: {
                        tokenizer: "standard",
                        filter: ["standard", "lowercase", "title_typeahead_filter"]
                    }
                },
                filter: {
                    title_typeahead_filter: {
                        type: "edgeNGram",
                        min_gram: 1,
                        max_gram: 256
                    }
                }
            }
        },
        mappings: {
            dict1: {
                properties: {
                    title: {
                        type: "string",
                        fields: {
                            typeahead: {
                                type: "string",
                                index_analyzer: "titles_analyzer",
                                search_analyzer: "standard"
                            }
                        }
                    },
                    description: {type: "string"}
                }
            }
        }
    }'

    curl -XPOST $host/vt/dict1/?pretty=true -d '{
        title: ["Навальніца", "Бальніца"],
        description: "1. Атмасферная з'"'"'ява — дождж з громам і маланкай. Ліпеньскія навальніцы. Ваенная н. (пераноснае значэнне: грозныя падзеі). 2. пераноснае значэнне: Бяда, небяспека. || прыметнік: навальнічны."
    }'

    curl -XPOST $host/vt/_refresh?pretty=true
fi

curl -XGET $host/vt/dict1/_search?pretty=true -d '{
    query: {
        match: {"title.typeahead": "ба"}
    }
}'

curl -XGET $host/vt/dict1/_search?pretty=true -d '{
    query: {
        match: {"title.typeahead": "бал"}
    }
}'

curl -XGET $host/vt/dict1/_search?pretty=true -d '{
    query: {
        match: {"title.typeahead": "бальн"}
    }
}'

curl -XGET $host/vt/dict1/_search?pretty=true -d '{
    query: {
        match: {"title": "бальн"}
    }
}'

curl -XGET $host/vt/dict1/_search?pretty=true -d '{
    query: {
        match: {"title": "навальніца"}
    }
}'
