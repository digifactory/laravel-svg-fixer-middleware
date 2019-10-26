<?php

namespace DigiFactory\SvgFixer;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SvgFixerMiddleware
{
    protected $response;
    protected $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';

    public function handle(Request $request, Closure $next)
    {
        // Check if request is a POST request and has at least one file
        if ($request->method() === 'POST' && $request->files->count() > 0) {
            /** @var UploadedFile $file */
            foreach ($request->files as $file) {
                // Check if uploaded file is an SVG
                if (Str::startsWith($file->getMimeType(), 'image/svg')) {
                    $this->handleSVGFile($file);
                }
            }
        }

        return $next($request);
    }

    private function handleSVGFile($file)
    {
        $handle = fopen($file->getPathname(), 'r+');
        $contents = fread($handle, filesize($file->getPathname()));

        rewind($handle);
        // Check if uploaded file is an SVG with starting XML declaration.
        // If not then we add the XML declaration.
        if (Str::startsWith($contents, '<svg')) {
            fwrite($handle, $this->xmlDeclaration.PHP_EOL.$contents);
        }

        fclose($handle);
    }
}
