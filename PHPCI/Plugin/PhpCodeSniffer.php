<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;

/**
* PHP Code Sniffer Plugin - Allows PHP Code Sniffer testing.
* @author       Dan Cryer <dan@block8.co.uk>
* @package      PHPCI
* @subpackage   Plugins
*/
class PhpCodeSniffer implements \PHPCI\Plugin
{
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var array
     */
    protected $suffixes;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $standard;

    /**
     * @var string
     */
    protected $tab_width;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @param \PHPCI\Builder $phpci
     * @param array $options
     */
    public function __construct(\PHPCI\Builder $phpci, array $options = array())
    {
        $this->phpci        = $phpci;
        $this->suffixes     = isset($options['suffixes']) ? (array)$options['suffixes'] : array('php');
        $this->directory    = isset($options['directory']) ? $options['directory'] : $phpci->buildPath;
        $this->standard     = isset($options['standard']) ? $options['standard'] : 'PSR2';
        $this->tab_width    = isset($options['tab_width']) ? $options['tab_width'] : '';
        $this->encoding     = isset($options['encoding']) ? $options['encoding'] : '';
    }

    /**
    * Runs PHP Code Sniffer in a specified directory, to a specified standard.
    */
    public function execute()
    {
        $ignore = '';
        if (count($this->phpci->ignore)) {
            $ignore = ' --ignore=' . implode(',', $this->phpci->ignore);
        }

        if (strpos($this->standard, '/') !== false) {
            $standard = ' --standard='.$this->directory.$this->standard;
        } else {
            $standard = ' --standard='.$this->standard;
        }

        $suffixes = '';
        if (count($this->suffixes)) {
            $suffixes = ' --extensions=' . implode(',', $this->suffixes);
        }

        $tab_width = '';
        if (strlen($this->tab_width)) {
            $tab_width = ' --tab-width='.$this->tab_width;
        }

        $encoding = '';
        if (strlen($this->encoding)) {
            $encoding = ' --encoding='.$this->encoding;
        }

        $cmd = PHPCI_BIN_DIR . 'phpcs %s %s %s %s %s "%s"';
        return $this->phpci->executeCommand($cmd, $standard, $suffixes, $ignore, $tab_width, $encoding, $this->phpci->buildPath);
    }
}
