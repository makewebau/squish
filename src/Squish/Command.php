<?php

namespace MakeWeb\Squish;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command
{
    protected $input;
    protected $ouput;
    protected $cli;

    public function setStyle(SymfonyStyle $style)
    {
        $this->style = $style;

        return $this;
    }

    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    protected function confirm($question, $defaultAnswer = false)
    {
        return ($this->cli->getHelperSet()->get('question'))->ask(
            $this->input, $this->output, new ConfirmationQuestion($question.' ', $defaultAnswer)
        );
    }

    protected function ask($question, $defaultAnswer = null)
    {
        $defaultText = !empty($defaultAnswer) ? "? [<comment>$defaultAnswer</comment>]" : '? ';

        return ($this->cli->getHelperSet()->get('question'))->ask(
            $this->input,
            $this->output,
            new Question($question.$defaultText, $defaultAnswer)
        );
    }

    protected function outputGreen($text)
    {
        return $this->output('<info>'.$text.'</info>');
    }

    protected function outputWhite($text)
    {
        return $this->output($text);
    }

    protected function outputYellow($text)
    {
        return $this->output('<comment>'.$text.'</comment>');
    }

    protected function output($text)
    {
        return $this->style->writeln($text);
    }
}
