<?php

namespace AppBundle\TwigExtension;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of twigExt
 *
 * @author Steven
 */
class twigExt extends \Twig_Extension implements \Twig\Extension\GlobalsInterface {

	protected $em;
	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public function getGlobals() {

		$count_em = $this->em->getRepository('AppBundle:message');
		$count_dql = $count_em->createQueryBuilder('c')
			->select('count(c.flag)')
			->where('c.flag = 0');
		$flag_count = $count_dql->getQuery();
		$count = $flag_count->getSingleScalarResult();

		return array('count' => $count);
	}

}
