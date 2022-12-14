<?php

namespace YOOtheme;

return [
    'transforms' => [
        'render' => function ($node) {
            /**
             * @var Encrypter $encrypter
             * @var Metadata  $meta
             */
            [$encrypter, $meta] = app(Encrypter::class, Metadata::class);

            $provider = (array) $node->props['provider'];

            $node->form = ['action' => Url::route('theme/newsletter/subscribe')];
            $node->settings = $encrypter->encrypt(
                array_merge($provider, (array) $node->props[$provider['name']])
            );

            $meta->set('script:newsletter', [
                'src' => Path::get('../../app/newsletter.min.js'),
                'defer' => true,
            ]);
        },
    ],

    'updates' => [
        '1.22.0-beta.0.1' => function ($node) {
            Arr::updateKeys($node->props, ['gutter' => 'gap']);
        },

        '1.20.0-beta.1.1' => function ($node) {
            Arr::updateKeys($node->props, ['maxwidth_align' => 'block_align']);
        },
    ],
];
