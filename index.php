<?php

	/**
	 * Classe PHP pour l'utilisation du WebService FreeMobile d'envoi de SMS
	 * Configuration !
	 * - Rdv dans l'espace freemobile>Gérer mon compte>Notifications par SMS>Activer
	 * - Récupérer les identifiants de connexion affichés  pour emettre votre requete
	 *	Plus d'infos sur l'activation du service sur https://www.domotique-info.fr/2014/06/nouvelle-api-sms-chez-free/
	 */
	class RequeteSMSFreeMobile
	{
		static $identifiants;
		static $api_endpoint = '"https://smsapi.free-mobile.fr/sendmsg';


		function __construct($args = array() )
		{
			self::$identifiants['user'] = '';	//	Votre identifiant FreeMobile
			self::$identifiants['pass'] = '';	//	Le code généré lors de l'activation du service de notifcation
		}

		function setIdentifiantsConnexion($user, $pass)
		{
			self::$identifiants['user'] = $user;
			self::$identifiants['pass'] = $pass;
		}


		function envoyerSMS($sms)
		{
			//securite
			if(empty($sms))
				exit('Veuillez spécifier un SMS valide.');

			if(!isset(self::$identifiants['user']) || !isset(self::$identifiants['pass']))
				exit('Identifiants de connexions non spécifiés !');


			//envoi requete
			$sms = urlencode($sms);
			$get = @file_get_contents(self::$api_endpoint."?user=".self::$identifiants['user']."&pass=".self::$identifiants['pass']."&msg=$sms");

			//reponse
			if(!$get)
				echo "L'endpoint de l'API Free Mobile ne semble pas repondre.";
			else
				$this->http_header_to_response($http_response_header);
		}


		function http_header_to_response($http_header)
		{
			$check = explode(' ', $http_header[0])[1];
			$msg = 'N/A';
			switch ($check) {
				case ($check == '200'):
					$msg = 'SMS envoyé avec succès !';
					break;
				case ($check == '400'):
					$msg = 'Un des paramètres obligatoires est manquant.';
					break;
				case ($check == '402'):
					$msg = 'Trop de SMS ont été envoyés en peu de temps. Veuillez réessayer dans quelques minutes.';
					break;
				case ($check == '403'):
					$msg = 'Login / Mot de passe incorrect. API SMS refuse cette connexion.';
					break;
				case ($check == '500'):
					$msg = 'Erreur côté serveur, veuillez réessayer plus tard.';
					break;
				
				default:
					$msg = 'Erreur inconnue de la fonction "'.__FUNCTION__.'" - ligne n°'.__LINE__.' - fichier: '.__FILE__;
					break;
			}

			echo $msg;
		}


	}





	//	Envoyer une requête
	$q = new RequeteSMSFreeMobile;
	$q->setIdentifiantsConnexion('username', 'password');
	$q->envoyerSMS("Hello world ! C'est mon premier SMS !");


