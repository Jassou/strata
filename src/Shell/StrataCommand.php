<?php
namespace Strata\Shell;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

/**
 * Base class for Shell Command reflection.
 * This class contains a basic toolset to perform repetitive visual outputs.
 * It is also the interface between Strata and Symfony's codebase.
 */
class StrataCommand extends Command
{
    /**
     * A tree representation prefix.
     *
     * @var string
     */
    protected $_tree_line = "├── ";

    /**
     * The bottom part of a tree representation prefix.
     *
     * @var string
     */
    protected $_tree_end = "└── ";

    /**
     * A reference to the current input interface object
     *
     * @var Symfony\Component\Console\Input\InputInterface
     */
    protected $_input = null;

    /**
     * A reference to the current output interface object
     *
     * @var Symfony\Component\Console\Output\OutputInterface
     */
    protected $_output = null;

    /**
     * Creates a visual representation of a tree branch. This is useful when
     * generating a list of files.
     * @param  boolean $isEnd Specifies if we are at the end of a list
     * @return string         The correct characters based on the <code>$isEnd</code> context.
     */
    public function tree($isEnd = false)
    {
        return $isEnd ? $this->_tree_end : $this->_tree_line;
    }

    /**
     * Creates a visual representation of an OK status. This is useful when performing
     * an action that can fail or be skipped.
     * @param  string $msg The optional message associated to the status.
     * @return string      A colored string
     */
    public function ok($msg = "")
    {
        return "<info>[ OK ]</info> " . $msg;
    }

    /**
     * Creates a visual representation of a skipped status. This is useful when performing
     * an action that can fail or succeed.
     * @param  string $msg The optional message associated to the status.
     * @return string      A colored string
     */
    public function skip($msg = "")
    {
        return "<fg=cyan>[SKIP]</fg=cyan> " . $msg;
    }

    /**
     * Creates a visual representation of a failed status. This is useful when performing
     * an action that can be skipped or succeed.
     * @param  string $msg The optional message associated to the status.
     * @return string      A colored string
     */
    public function fail($msg = "")
    {
        return "<error>[FAIL]</error> " . $msg;
    }

    /**
     * Return a new line.
     * @return string      An empty line break.
     */
    public function nl()
    {
        return $this->_output->writeLn('');
    }

    /**
     * The startup function should be called each time a command is being executed. It
     * saves the Input and Output interfaces to allow the command to use it further down
     * the process.
     * @param  InputInterface  $input  The current input interface
     * @param  OutputInterface $output The current output interface
     * @return null
     */
    public function startup(InputInterface $input, OutputInterface $output)
    {
        $this->_input = $input;
        $this->_output = $output;
    }

    /**
     * The shutdown function should be called each time a command has completed execution.
     * @return null
     */
    public function shutdown()
    {

    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        return parent::configure();
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param Symfony\Component\Console\Input\InputInterface  $input  An InputInterface instance
     * @param Symfony\Component\Console\Output\OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return parent::execute($input, $output);
    }
}
