<?php


namespace SkyDiablo\DoctrineBundle\Model\Common;

use SkyDiablo\DoctrineBundle\Exception\Common\VersionException;

/**
 * @author SkyDiablo <skydiablo@gmx.net>
 * Class Version
 */
class Version
{
    const MAJOR = 'major';
    const MINOR = 'minor';
    const REVISION = 'revision';
    const BUILD = 'build';
    const COLUMN_LIMIT = 255;
    const DEFAULT_FORMAT = '%a.%i.%e.%u';
    const DEFAULT_OPTIONAL_FORMAT = '%a?\.?%i?\.?%e?\.?%u?';

    private $major;
    private $minor;
    private $revision;
    private $build;

    /**
     * @param int $major
     * @param int $minor
     * @param int $revision
     * @param int $build
     */
    private function __construct($major = 0, $minor = 0, $revision = 0, $build = 0)
    {
        $this
            ->setMajor($major)
            ->setMinor($minor)
            ->setRevision($revision)
            ->setBuild($build);
    }

    /**
     * @param int $major
     * @param int $minor
     * @param int $revision
     * @param int $build
     *
     * @return Version
     */
    public static function create($major = 0, $minor = 0, $revision = 0, $build = 0)
    {
        return new self($major, $minor, $revision, $build);
    }

    /**
     * @param Version $version
     * @return bool
     */
    public function equal(Version $version)
    {
        return $this->compare($version) === 0;
    }

    /**
     * @param Version $version
     * @return bool
     */
    public function greater(Version $version)
    {
        return $this->compare($version) > 0;
    }

    /**
     * @param Version $version
     * @return bool
     */
    public function greaterEqual(Version $version)
    {
        return $this->compare($version) >= 0;
    }

    /**
     * @param Version $version
     * @return bool
     */
    public function lower(Version $version)
    {
        return $this->compare($version) < 0;
    }

    /**
     * @param Version $version
     * @return bool
     */
    public function lowerEqual(Version $version)
    {
        return $this->compare($version) <= 0;
    }

    /**
     * compare function for -1|0|+1
     * @param Version $version
     * @return int
     */
    public function compare(Version $version)
    {
        return $this->toLong() <=> $version->toLong();
    }

    /**
     * version as single int
     * @return int
     */
    public function toLong()
    {
        return ip2long($this->format(self::DEFAULT_FORMAT));
    }

    /**
     * Create Version by String and Format
     * Format:
     *   %a : mAjor
     *   %i : mInor
     *   %e : rEvision
     *   %u : bUild
     *
     * @param string $haystack
     * @param string $format
     * @param bool $quoteFormat
     *
     * @return Version|null
     */
    static public function parse($haystack, $format = self::DEFAULT_OPTIONAL_FORMAT, $quoteFormat = true)
    {
        if (is_string($haystack) && strlen($haystack) >= 1) {
            $search = ['%a', '%i', '%e', '%u'];
            $replace = array_map(function ($column) {
                return sprintf('(?P<%s>[\d]{1,3})', $column);
            }, [self::MAJOR, self::MINOR, self::REVISION, self::BUILD]);
            $format = ($quoteFormat && (strcmp($format, self::DEFAULT_OPTIONAL_FORMAT) <> 0)) ? preg_quote($format, '!') : $format;
            $regex = str_replace($search, $replace, $format);
            $results = [];
            if (preg_match('!' . $regex . '!', (string)$haystack, $results)) {
                if (isset($results[self::MAJOR]) || isset($results[self::MINOR]) || isset($results[self::REVISION]) || isset($results[self::BUILD])) {
                    return self::create(
                        isset($results[self::MAJOR]) ? (int)$results[self::MAJOR] : 0,
                        isset($results[self::MINOR]) ? (int)$results[self::MINOR] : 0,
                        isset($results[self::REVISION]) ? (int)$results[self::REVISION] : 0,
                        isset($results[self::BUILD]) ? (int)$results[self::BUILD] : 0
                    );
                }
            }
        }
        return null;
    }

    /**
     * Format:
     *   %a : mAjor
     *   %i : mInor
     *   %e : rEvision
     *   %u : bUild
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format = self::DEFAULT_FORMAT)
    {
        $search = ['%a', '%i', '%e', '%u'];
        $replace = [
            $this->getMajor(),
            $this->getMinor(),
            $this->getRevision(),
            $this->getBuild()
        ];
        return str_replace($search, $replace, $format);
    }

    /**
     * @return int
     */
    public function getMinor()
    {
        return (int)$this->minor;
    }

    /**
     * @return int
     */
    public function getMajor()
    {
        return (int)$this->major;
    }

    /**
     * @return int
     */
    public function getRevision()
    {
        return (int)$this->revision;
    }

    /**
     * @return int
     */
    public function getBuild()
    {
        return (int)$this->build;
    }

    /**
     * @param $minor
     *
     * @return $this
     * @throws VersionException
     */
    public function setMinor($minor)
    {
        $this->minor = $this->versionColumnValidate($minor);
        return $this;
    }

    /**
     * @param $major
     *
     * @return $this
     * @throws VersionException
     */
    public function setMajor($major)
    {
        $this->major = $this->versionColumnValidate($major);
        return $this;
    }

    /**
     * @param $revision
     *
     * @return $this
     * @throws VersionException
     */
    public function setRevision($revision)
    {
        $this->revision = $this->versionColumnValidate($revision);
        return $this;
    }

    /**
     * @param $build
     *
     * @return $this
     * @throws VersionException
     */
    public function setBuild($build)
    {
        $this->build = $this->versionColumnValidate($build);
        return $this;
    }

    /**
     * @param $value
     *
     * @return int
     * @throws VersionException
     */
    protected function versionColumnValidate($value)
    {
        $value = (int)abs($value);
        if ($value > self::COLUMN_LIMIT) {
            throw VersionException::ColumnLimit($value, self::COLUMN_LIMIT);
        }
        return $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format(self::DEFAULT_FORMAT);
    }
}
