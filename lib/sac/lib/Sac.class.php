<?php

/**
 * Implements the service Certissim including every webservices access methodes
 *
 * @author ESPIAU Nicolas <nicolas.espiau at fia-net.com>
 */
class Sac extends Service
{

	const INPUT_TYPE = 'text';
	const IDSEPARATOR = '^';
	const CONSULT_MODE_MINI = 'mini';
	const CONSULT_MODE_FULL = 'full';

	/**
	 * builds the HTML form that submits the XML stream by redirection (redirect.cgi)
	 * 
	 * @param string $controlcallback order stream
	 * @param string $urlcallback 
	 * @param string $paracallback
	 * @param string $submittype
	 * @param string $imagepath
	 * @return Form
	 */
	public function generateRedirectForm($controlcallback, $urlcallback, $paracallback, $submittype = Form::SUBMIT_STANDARD, $imagepath = null)
	{
		//définition des différents champs du form
		$fields = array(
			'siteid' => array('type' => Sac::INPUT_TYPE, 'name' => 'siteid', 'value' => $this->getSiteId()),
			'controlcallback' => array('type' => Sac::INPUT_TYPE, 'name' => 'controlcallback', 'value' => preg_replace('#"#', "'", $controlcallback)),
			'urlcallback' => array('type' => Sac::INPUT_TYPE, 'name' => 'urlcallback', 'value' => $urlcallback),
			'paracallback' => array('type' => Sac::INPUT_TYPE, 'name' => 'paracallback', 'value' => $paracallback),
		);

		//instanciation du form
		$form = new Form($this->getUrlredirect(), 'submit_fianet_xml', 'POST', $fields);

		//ajout du submit
		switch ($submittype)
		{
			case Form::SUBMIT_IMAGE:
				$form->addImageSubmit($imagepath, 'payer', 'Payer', 'Payer', 'image_sumbit');
				break;

			case Form::SUBMIT_STANDARD:
				$form->addSubmit();
				break;

			case Form::SUBMIT_AUTO:
				$form->setAutosubmit(true);
				break;

			default:
				$msg = "Type submit non reconnu.";
				insertLog(get_class($this)." - generateRedirectForm()", $msg);
				break;
		}

		return $form;
	}

	/**
	 * sends a transaction to Certissim using the script singet.cgi and the method POST and returns the response
	 *
	 * @param string $xml order stream
	 * @return string
	 */
	public function sendSinget($xml)
	{
		$data = array(
			'siteid' => $this->getSiteId(),
			'controlcallback' => $xml,
		);
		$con = new FianetSocket($this->getUrlsinget(), 'POST', $data);
		$res = $con->send();
		return $res;
	}

	/**
	 * sends a transactions stack using stacking.cgi and returns the response
	 *
	 * @param string $stack
	 * @return string
	 */
	public function sendStacking($stack)
	{
		$data = array(
			'siteid' => $this->getSiteId(),
			'controlcallback' => $stack,
		);
		$con = new FianetSocket($this->getUrlstacking(), 'GET', $data);
		return $con->send();
	}

	/**
	 * sends a transactions stack using stacking.cgi and returns stackfast.cgi
	 *
	 * @param string $stack
	 * @return string
	 */
	public function sendStackfast($stack)
	{
		$data = array(
			'siteid' => $this->getSiteId(),
			'controlcallback' => $stack,
		);
		$con = new FianetSocket($this->getUrlstacking(), 'POST', $data);
		return $con->send();
	}

	/**
	 * calls the get_validation.cgi script to get the score of the transaction $refid and returns the response
	 *
	 * @param string $refid merchant order identifier
	 * @param string $mode answer mode: mini|full
	 * @param bool $repFT displays FT answer or not
	 * @return string
	 */
	public function getValidation($refid, $mode = 'mini', $repFT = '0')
	{
		$data = array(
			'SiteID' => $this->getSiteId(),
			'Pwd' => $this->getPassword(),
			'RefID' => $refid,
			'Mode' => $mode,
			'RepFT' => $repFT
		);
		$con = new FianetSocket($this->getUrlgetvalidation(), 'POST', $data);
		return $con->send();
	}

	/**
	 * calls the get_redirect_validation script to get the score of the transaction $refid and returns the response
	 *
	 * @param string $refid merchant order identifier
	 * @param string $mode answer mode: mini|full
	 * @param bool $repFT displays FT answer or not
	 * @param string $urlback URL whereto send the response
	 * @return string
	 */
	public function getRedirectValidation($refid, $mode = Sac::CONSULT_MODE_MINI, $urlback = null, $repFT = '0')
	{
		$data = array(
			'SiteID' => $this->getSiteId(),
			'Pwd' => $this->getPassword(),
			'RefID' => $refid,
			'Mode' => $mode,
			'RepFT' => $repFT,
			'urlBack' => (!is_null($urlback) ? $urlback : $this->getUrldefaultredirectvaildationurlback()),
		);
		$con = new FianetSocket($this->getUrlredirectvalidation(), 'POST', $data);
		return $con->send();
	}

	/**
	 * returns the transactions scores list of orders that has their identifier in $listId
	 *
	 * @param array $listId merchant orders identifiers
	 * @param string $mode answer mode: mini|full
	 * @param bool $repFT displays FT answer or not
	 * @return string
	 */
	public function getValidstackByReflist(array $listId, $mode = Sac::CONSULT_MODE_MINI, $repFT = '0')
	{
		$list = '';
		foreach ($listId as $rid)
		{
			$list .= $rid.Sac::IDSEPARATOR;
		}

		$list = preg_replace('#^(.+)'.Sac::IDSEPARATOR.'$#', '$1', $list);

		$data = array(
			'SiteID' => $this->getSiteId(),
			'Pwd' => $this->getPassword(),
			'Mode' => $mode,
			'RepFT' => $repFT,
			'ListID' => $list,
			'Separ' => Sac::IDSEPARATOR
		);
		return $this->getValidstack($data);
	}

	/**
	 * returns the transactions scores list of orders created the $date
	 *
	 * @param array $date
	 * @param int $numpage page index to read
	 * @param string $mode answer mode: mini|full
	 * @param bool $repFT displays FT answer or not
	 * @return string
	 */
	public function getValidstackByDate($date, $numpage, $mode = Sac::CONSULT_MODE_MINI, $repFT = '0')
	{
		if (!preg_match('#^[0-9]{2}/[0-1][0-9]/[0-9]{4}$#', $date))
		{
			$msg = "La date '$date' n'est pas au bon format. Format attendu : dd/mm/YYYY";
			insertLog(get_class($this)." - getValidstackByDate()", $msg);
			throw new Exception($msg);
		}

		$data = array(
			'SiteID' => $this->getSiteId(),
			'Pwd' => $this->getPassword(),
			'Mode' => $mode,
			'RepFT' => $repFT,
			'DtStack' => $date,
			'Ind' => $numpage
		);
		return $this->getValidstack($data);
	}

	/**
	 * calls the script validstack and returns the response
	 *
	 * @param array $param
	 * @return string
	 */
	private function getValidstack($param)
	{
		$con = new FianetSocket($this->getUrlgetvalidstack(), 'POST', $param);
		return $con->send();
	}

	/**
	 * calls Certissim to get updated scores
	 * 
	 * @param string $mode all|new|old, allow to get only new updates (never seen), old updates (already seen), or all updates
	 * @param type $output answer mode: mini|full
	 * @param type $repFT displays FT answer or not
	 * @return string
	 */
	public function getAlert($mode = 'new', $output = 'mini', $repFT = '0')
	{
		$data = array(
			'SiteID' => $this->getSiteId(),
			'Pwd' => $this->getPassword(),
			'Mode' => $mode,
			'Output' => $output,
			'RepFT' => $repFT,
		);
		$con = new FianetSocket($this->getUrlgetalert(), 'POST', $data);
		return $con->send();
	}

	/**
	 * returns the URL of the VCD order page of the order $rid
	 *
	 * @param string $rid merchant order ref
	 * @return string
	 */
	public function getVisuCheckUrl($rid)
	{
		$url = $this->getUrlvisucheckdetail();
		$url .= '?sid='.$this->getSiteid().'&log='.$this->getLogin().'&pwd='.$this->getPasswordurlencoded()."&rid=$rid";

		return $url;
	}

}