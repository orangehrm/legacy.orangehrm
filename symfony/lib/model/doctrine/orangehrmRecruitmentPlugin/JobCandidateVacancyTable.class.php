<?php

/**
 * JobCandidateVacancyTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class JobCandidateVacancyTable extends PluginJobCandidateVacancyTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object JobCandidateVacancyTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('JobCandidateVacancy');
    }
}