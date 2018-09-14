<?php
/**
 * Author: Xavier Au
 * Date: 14/9/2018
 * Time: 2:39 PM
 */

namespace Anacreation\Cms\Contracts;


interface AnalyticUrlBuilderInterface
{

    /**
     * AnalyticUrlBuilderInterface constructor.
     * @param string $url
     * @param string $source
     */


    public function get(): string;

    public function setUrl(string $url): AnalyticUrlBuilderInterface;

    public function setSource(string $source): AnalyticUrlBuilderInterface;

    public function setMedium(string $medium): AnalyticUrlBuilderInterface;

    public function setName(string $name): AnalyticUrlBuilderInterface;

    public function setTerm(string $term): AnalyticUrlBuilderInterface;

    public function setContent(string $content): AnalyticUrlBuilderInterface;
}