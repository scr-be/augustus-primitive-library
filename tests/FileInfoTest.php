<?php

/*
 * This file is part of the `src-run/augustus-primitive-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Primitive\Tests;

use SR\Primitive\FileInfo;

/**
 * Class FileInfoTest.
 */
class FileInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testAbsoluteToRelativePath()
    {
        $path = '/foo/bar/file.ext';
        $relativeTo = '/foo/foo/';

        $this->assertSame('../bar/file.ext', FileInfo::absoluteToRelativePath($path, $relativeTo));

        $path = '/foo/bar/file.ext';
        $relativeTo = '/bar/foo/';

        $this->assertSame('../../foo/bar/file.ext', FileInfo::absoluteToRelativePath($path, $relativeTo));

        $path = '/foo/bar/file.ext';
        $relativeTo = 'bar/foo/';
        
        $this->expectException('SR\Exception\InvalidArgumentException');

        $this->assertSame('../../foo/bar/file.ext', FileInfo::absoluteToRelativePath($path, $relativeTo));
    }

    public function testConstructor()
    {
        $fileInfo = new FileInfo(__FILE__);

        $this->assertSame(__FILE__, $fileInfo->getRealPath());
        $this->assertSame(__DIR__, $fileInfo->getPath());
    }

    public function testSize()
    {
        $file = new FileInfo(__FILE__);
        $sizeHuman = $file->getSizeHuman(2);

        $this->assertTrue(substr($sizeHuman, -1, 1) === 'K');
    }

    public function testCreateFromSpl()
    {
        $splFile = new \SplFileInfo('/foo/bar/file.ext');
        $file = FileInfo::createFromSplFileInfo($splFile);

        $this->assertInstanceOf('SR\Primitive\FileInfo', $file);
        $this->assertSame('/foo/bar/file.ext', $file->getPathname());
    }
}

/* EOF */
