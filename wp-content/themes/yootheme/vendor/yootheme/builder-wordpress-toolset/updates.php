<?php

namespace YOOtheme;

use YOOtheme\Builder\Wordpress\Toolset\Helper;

return [
    '3.0.0-beta.1.9' => function ($node, array $params) {
        if (!empty($node->source->props) && !empty($node->source->query->name)) {
            foreach ((array) $node->source->props as $prop) {
                if (!empty($prop->name) && str_contains($prop->name, 'toolset.')) {
                    $parts = explode('.', $prop->name);
                    $index = array_search('toolset', $parts);

                    if ($index !== count($parts) - 2) {
                        continue;
                    }

                    if (!($domain = Helper::getDomainFromNode($node))) {
                        continue;
                    }

                    $fields = Helper::fields($domain, array_slice($parts, $index + 1, 1), false);

                    $field = array_pop($fields);
                    if (Arr::get($field, 'type') === 'image') {
                        $prop->name .= '.url';
                    }

                    if (Arr::get($field, 'type') === 'google_address') {
                        $prop->name .= '.coordinates';
                    }
                }
            }
        }
    },

    '2.6.3' => function ($node, array $params) {
        if (
            !empty($node->source->query->field->name) &&
            str_contains($node->source->query->field->name, 'toolset.')
        ) {
            $node->source->query->field->name = implode(
                '.',
                array_map(
                    [Str::class, 'snakeCase'],
                    explode('.', $node->source->query->field->name)
                )
            );
        }

        if (!empty($node->source->props)) {
            foreach ((array) $node->source->props as $prop) {
                if (!empty($prop->name) && str_contains($prop->name, 'toolset.')) {
                    $prop->name = implode(
                        '.',
                        array_map([Str::class, 'snakeCase'], explode('.', $prop->name))
                    );
                }
            }
        }
    },
];
