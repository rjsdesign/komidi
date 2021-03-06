<?php

	class Spectacle
	{
		private $db;

		function __construct($DB_cnx)
		{
			$this->db = $DB_cnx;
		}

		public function getSpectacles()
		{
			$stmt = $this->db->prepare("SELECT * FROM db_komidi ");
			$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $editRow;
		}
		//receuille lemail
		public function getEmail($req){
			$stmt = $this->db->prepare($req);
			$stmt->execute();
			$editRow=$stmt->fetch(PDO::FETCH_ASSOC);
			return $editRow->rowCount();
		}
		//ajoute un menbres
		public function setMembre($req){
			$stmt = $this->db->prepare($req);
			$stmt->execute();

		}
		//methode pour recuperer la vue prend en parametre l'id du spectacle
		public function getVueNote($id){

			$strSQL= "SELECT * FROM kdi_listenbNote_Moyenne WHERE Spe_id=$id;";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $edit;
		}
		//recuperer les 5 meilleur spectacle
		public function get5Spectacle(){

			$strSQL= "SELECT Spe_titre,K.Spe_id FROM kdi_spectacle K, cinqBestSpectacle C WHERE C.Spe_id = K.spe_id;";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			//$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			//return $edit;
			return $stmt;

		}
		//verifie si le membres existe
		public function verifMembre($idMembre){
			$strSQL= "SELECT mem_id FROM menbres WHERE mem_id = $idMembre;";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			//$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
		}

		//cherche le texte
		public function recherche($texte){
			$strSQL= "SELECT Spe_titre,Spe_id,Spe_affiche FROM kdi_spectacle WHERE Spe_titre LIKE \"%$texte%\" OR Spe_acteur LIKE \"%$texte%\" OR Spe_genre LIKE \"$texte%\" ;";
			//bbro i was ir
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			return $stmt; //->fetch(PDO::FETCH_ASSOC);
			//return $edit;
		}

		//verifie si un spectacle existe
		public function verifSpectacle($idSpectacle){
			$strSQL= "SELECT Spe_id FROM kdi_spectacle WHERE Spe_id = $idSpectacle;";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			//$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
		}
		//regarde si la personne existe dans la baase de donner pour ce connecter
		public function verifConnection($email,$mdp){
			$strSQL= "SELECT mem_email,mem_pass,mem_statut FROM menbres WHERE mem_email = \"$email\" AND mem_pass = \"$mdp\";";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			if( $stmt->rowCount() == 1 ){
				$edit=$stmt->fetch(PDO::FETCH_ASSOC);
				if( $edit['mem_statut'] == 0 ){
					return "u";
				}
				else if( $edit['mem_statut'] == 1 ){
					return "a";
				}
				else{
					return "e";
				}
			}
			else if( $stmt->rowCount() == 0 ){
				return "e";
			}
			else{
				return "e";
			}

		}
		//recupere des infosur l(utilisateur
		public function getUtilisateur($mail,$mdp){
			$strSQL= "SELECT * FROM menbres WHERE mem_email = \"$mail\" AND mem_pass = \"$mdp\";";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $edit;
		}
		//verifie si la personne a déja voter
		public function verifNoter($idMembre,$idSpectacle){
			//faire envoyer la variable id membres de sessio aussi pour verifier si la personne ne tente ppas une usurpation d' id membres
			$strSQL= "SELECT mem_id,Spe_id FROM noter WHERE mem_id =$idMembre  AND Spe_id = $idSpectacle;";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			//$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
		}
		//enregistre le vote de la personne
		public function noter($idMembre,$idSpectacle,$note){
			//echo $this->verifNoter($idMembre,$idSpectacle).";".$this->verifMembre($idMembre).";".$this->verifSpectacle($idSpectacle);

			if( $this->verifNoter($idMembre,$idSpectacle) == 1 or $this->verifMembre($idMembre) == 0 or $this->verifSpectacle($idSpectacle) == 0){
				return "0";
			}
			else{
				$strSQL= "INSERT INTO noter VALUES($idMembre,$idSpectacle,$note);";
				$stmt = $this->db->prepare($strSQL);
				$stmt->execute();
				return "1";
			}


		}

		public function getSpectacle($id)//recupere les info lier a un spectacle
		{

			$strSQL= "SELECT Spe_id, Spe_titre, Spe_mes, Spe_genre,Spe_Lang, Spe_resume_court, Spe_affiche, Spe_public, Spe_duree, Spe_resume_long FROM kdi_spectacle WHERE Spe_id=".$id.";";
			$stmt = $this->db->prepare($strSQL);
			$stmt->execute();
			$edit=$stmt->fetch(PDO::FETCH_ASSOC);
			return $edit;
		}


		public function updateSpectacle($params)
		{

		}

		public function deleteSpectacle($id)
		{

		}


		/* paging */

		public function dataview($query)
		{
			$stmt = $this->db->prepare($query);
			$stmt->execute();


			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					$id 		= $row['Spe_id'];
					$title 		= $row['Spe_titre'];
					$genre 		= $row['Spe_genre'];
					$public 	= $row['Spe_public'];
					$tailleresume = 100;
					$synopsis 	= substr($row['Spe_resume_court'], 0, $tailleresume).' [...]';
					$picture 	= getCover($row['Spe_affiche']);
					?>
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<a  href="index.php?action=getSpectacle&id=<?= $id ?>">
							<img class="img-rounded" src="<?= $picture ?>" class='img-rounded' width='150px' height='150px'>
						</a>
						<div class="caption">
							<h4><?= $title ?></h4>
							<ul class="list-unstyled">
								<li><?= $synopsis ?></li>
								<li><strong>Public :</strong><?= $public ?></li>
								<li><strong>Genre :</strong><?= $genre 	?></li>
							</ul>
						</div>
					</div>
					<?php
				}
			}
			else
			{
				echo  "<div class='caption'>
				<div class='alert alert-warning'>
				<span class='glyphicon glyphicon-info-sign'></span> 
				&nbsp; Inconnu ...</div></div>";
			}

		}

		public function paging($query,$records_per_page)
		{
			$starting_position=0;
			if(isset($_GET["page_no"]))
			{
				$starting_position=($_GET["page_no"]-1)*$records_per_page;
			}
			$query2=$query." limit $starting_position,$records_per_page";
			return $query2;
		}

		public function paginglink($query,$records_per_page)
		{

			$self = $_SERVER['PHP_SELF'];

			$stmt = $this->db->prepare($query);
			$stmt->execute();

			$total_no_of_records = $stmt->rowCount();

			if($total_no_of_records > 0)
			{
				?><ul class="pagination"><?php
				$total_no_of_pages=ceil($total_no_of_records/$records_per_page);
				$current_page=1;
				if(isset($_GET["page_no"]))
				{
					$current_page=$_GET["page_no"];
				}
				if($current_page!=1)
				{
					$previous =$current_page-1;
					echo "<li><a href='".$self."?page_no=1'>Premier</a></li>";
					echo "<li><a href='".$self."?page_no=".$previous."'>Précédent</a></li>";
				}
				for($i=1;$i<=$total_no_of_pages;$i++)
				{
					if($i==$current_page)
					{
						echo "<li><a href='".$self."?page_no=".$i."' style='color:red;'>".$i."</a></li>";
					}
					else
					{
						echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
					}
				}
				if($current_page!=$total_no_of_pages)
				{
					$next=$current_page+1;
					echo "<li><a href='".$self."?page_no=".$next."'>Suivant</a></li>";
					echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last</a></li>";
				}
				?></ul><?php
			}
		}

		/* paging */

	}


?>
