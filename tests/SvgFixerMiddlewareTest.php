<?php

namespace DigiFactory\SvgFixer\Tests;

use App\Http\Middleware\CheckUserStatus;
use DigiFactory\SvgFixer\SvgFixerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SvgFixerMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDummyRoutes();
    }

    /** @test */
    public function it_not_mutates_request_without_files()
    {
        $parameters = [
            'foo' => 'bar',
            'john' => 'doe',
        ];

        $request = Request::create('/image', 'POST', $parameters);

        $middleware = app(SvgFixerMiddleware::class);

        $middleware->handle($request, function (Request $request) use ($parameters) {
            $this->assertCount(2, $request->input());
            $this->assertCount(0, $request->allFiles());
            $this->assertEquals($parameters, $request->input());
        });
    }

    /** @test */
    public function it_not_mutates_request_with_files()
    {
        $file = UploadedFile::fake()
            ->image('avatar.jpg', 2000, 1500)
            ->size(1024);

        $files = [
            'file' => $file,
        ];

        $request = Request::create('/image', 'POST', [], [], $files);

        $middleware = app(SvgFixerMiddleware::class);

        $middleware->handle($request, function (Request $request) use ($files) {
            $this->assertCount(0, $request->input());
            $this->assertCount(1, $request->allFiles());
            $this->assertEquals($files, $request->allFiles());
        });
    }

    /** @test */
    public function it_mutates_request_with_invalid_svg()
    {
        $image = $this->createTemporarySvg('invalid.svg');

        $files = [
            'image' => $image,
        ];

        $request = Request::create('/image', 'POST', [], [], $files);

        $middleware = app(SvgFixerMiddleware::class);

        $middleware->handle($request, function (Request $request) use ($files) {
            $this->assertCount(0, $request->input());
            $this->assertCount(1, $request->allFiles());
            $this->assertEquals($files, $request->allFiles());

            $file = $request->files->get('image');

            $content = file_get_contents($file);

            $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $content);
        });

        $this->removeTemporarySvg($image);
    }

    /** @test */
    public function it_mutates_request_with_multiple_invalid_svgs()
    {
        $images = [
            $this->createTemporarySvg('invalid.svg', 'invalid1.svg'),
            $this->createTemporarySvg('invalid.svg', 'invalid2.svg'),
        ];

        $files = [
            'image1' => $images[0],
            'image2' => $images[1],
        ];

        $request = Request::create('/image', 'POST', [], [], $files);

        $middleware = app(SvgFixerMiddleware::class);

        $middleware->handle($request, function (Request $request) use ($files) {
            $this->assertCount(0, $request->input());
            $this->assertCount(2, $request->allFiles());
            $this->assertEquals($files, $request->allFiles());

            foreach ($files as $imageKey => $image) {
                $file = $request->files->get($imageKey);

                $content = file_get_contents($file);

                $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $content);
            }
        });

        foreach ($images as $image) {
            $this->removeTemporarySvg($image);
        }
    }

    /** @test */
    public function it_mutates_request_with_multiple_svgs()
    {
        $images = [
            $this->createTemporarySvg('invalid.svg'),
            $this->createTemporarySvg('valid.svg'),
        ];

        $files = [
            'image1' => $images[0],
            'image2' => $images[1],
        ];

        $request = Request::create('/image', 'POST', [], [], $files);

        $middleware = app(SvgFixerMiddleware::class);

        $middleware->handle($request, function (Request $request) use ($files) {
            $this->assertCount(0, $request->input());
            $this->assertCount(2, $request->allFiles());
            $this->assertEquals($files, $request->allFiles());

            foreach ($files as $imageKey => $image) {
                $file = $request->files->get($imageKey);

                $content = file_get_contents($file);

                $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $content);
            }
        });

        foreach ($images as $image) {
            $this->removeTemporarySvg($image);
        }
    }

    /** @test */
    public function it_mutates_request_with_invalid_svg_and_validates()
    {
        $image = $this->createTemporarySvg('invalid.svg');

        $files = [
            'image' => $image,
        ];

        $request = Request::create('/image', 'POST', [], [], $files);

        $middleware = app(SvgFixerMiddleware::class);

        $validatorFactory = $this->app['validator'];

        $validator = $validatorFactory->make($request->all(), [
            'image' => 'required|image',
        ]);

        $this->assertFalse($validator->passes());

        $middleware->handle($request, function (Request $request) use ($files, $validatorFactory) {
            $validator = $validatorFactory->make($request->all(), [
                'image' => 'required|image',
            ]);

            $this->assertTrue($validator->passes());
        });

        $this->removeTemporarySvg($image);
    }

    protected function createTemporarySvg($filename, $newFilename = null)
    {
        if (!$newFilename) {
            $newFilename = $filename;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        $path = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;

        $tmpPath = __DIR__ . DIRECTORY_SEPARATOR;

        copy($path . $filename, $tmpPath . $newFilename);

        return new \Illuminate\Http\UploadedFile(
            $tmpPath . $newFilename,
            $newFilename,
            $finfo->file($tmpPath . $newFilename),
            null,
            true
        );
    }

    protected function removeTemporarySvg(UploadedFile $file)
    {
        unlink($file->getPathname());
    }
}
