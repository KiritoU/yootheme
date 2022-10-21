<?php

namespace YOOtheme;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class ImageController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        Memory::raise();
    }

    /**
     * Gets the image file.
     *
     * @param Request       $request
     * @param Response      $response
     * @param ImageProvider $provider
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function get(Request $request, Response $response, ImageProvider $provider)
    {
        $src = $request->getQueryParam('src');
        $hash = $request->getQueryParam('hash');

        $request->abortIf($hash !== $provider->getHash($src), 400, 'Invalid image hash');

        [$file, $params] = json_decode(base64_decode($src));

        if (!($image = $provider->create($file))) {
            $request->abort(404, "Image '{$file}' not found");
        }

        if ($params) {
            $image = $image->apply($params);
        }

        if ($provider->cache && $request->getAttribute('save')) {
            $cache = Path::join($provider->cache, substr($image->getHash(), 0, 2));

            if (!File::makeDir($cache)) {
                $request->abort(500, 'Image cache dir not found');
            }

            $filename = $image->getFilename($cache);
        } else {
            $filename = fopen('php://temp', 'rw+');
        }

        if (!$image->save($filename)) {
            $request->abort(500, 'Image cache saving failed');
        }

        return $response
            ->withFile($filename, "image/{$image->getType()}")
            ->withHeader('Cache-Control', 'max-age=600, no-transform, must-revalidate');
    }
}
