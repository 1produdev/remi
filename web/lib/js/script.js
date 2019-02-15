var settings = {
	//height of sphere container
	height: 275,
	//width of sphere container
	width: 275,
	//radius of sphere
	radius: 87,
	//rotation speed
	speed: 1 ,
	//sphere rotations slower
	slower: 1,
	//delay between up<a href="https://www.jqueryscript.net/time-clock/">date</a> position
	timer: 37,
	//dependence of a font size on axis Z
	fontMultiplier: 20,
	//tag css stylies on mouse over
	hoverStyle: {
		border: 'none',
		color: '#ff0000'
	},
	//tag css stylies on mouse out
	mouseOutStyle: {
		border: '',
		color: ''
	}
};

var img_base = "/img_";
var img_src = "";

$(document).ready(function(){
	$('#tagcloud').tagoSphere(settings);
	//$('.tooltipped').tooltip();
	
	$("#tagcloud").trigger('mouseenter');
	$('.tooltipped').tooltip();
	
	add_listeners();
	$('#the_tags').children().css('list-style-type', 'circle');
	$('#the_tags').children().addClass('anim_tag');
	
	change_couleur();
});
$('#bottom_tags').mouseenter(function(){
	//$('#bottom_tags').css('position', 'fixed');
});
$('#bottom_tags').mouseleave(function(){
	//$('#bottom_tags').css('position', 'relative');
});
var enterInCard = false; var clicked = false; var clicked_on_card = false;
function add_listeners(){     
	$("#the_tags").children().each(function(){
		$(this).children().each(function(){
			$(this).mouseenter(function(){
				if(!$(this).hasClass('card')){
					$(this).addClass("card blue-grey darken-1 pulse");
					enterInCard = false;
				}
				else{
					enterInCard = true;
				}
			});
			$(this).click(function(){
				var th_txt = $(this).text();
				var th_txt_body = do_txt_body(th_txt);
				
				if($("#img").hasClass('slide-in-blurred-tr')){
					$("#img").removeClass('slide-in-blurred-tr');
					setTimeout(function(){ 
						$("#img").addClass('slide-in-blurred-tr');
					}, 10);
				}
				else{
					$("#img").addClass('slide-in-blurred-tr');
				}

				$("#img").attr('src', img_src);
				
				clicked = true;
				
				if(enterInCard){
					clicked_on_card = true;
				}
				else{
					clicked_on_card = false;
				}
				
				if($("#text_tag").hasClass('zoomIn')){
					$("#text_tag").removeClass('zoomIn');
					setTimeout(function(){ 
						$("#text_tag").addClass('zoomIn');
						$("#text_title").html(th_txt);
						$("#text_body").html(th_txt_body);
					}, 10);
				}
				else{
					$("#text_tag").addClass('zoomIn');
					$("#text_title").html(th_txt);
					$("#text_body").html(th_txt_body);
				}
				
				$(this).animate({
					opacity: 1,
					fontWeight: 900,
				  }, 500, function() {
					// Animation complete.
				  });
			});
			$(this).mouseleave(function(){
				if(!enterInCard && !clicked_on_card && !clicked){
					$(this).removeClass("card blue-grey darken-1 pulse");
				}
				clicked_on_card = false;
				clicked = false;
				enterInCard = false;
				
				$(this).animate({
					opacity: 0.8,
					fontWeight: 700,
				  }, 15000, function() {
					// Animation complete.
				  });
			});
		});
	});
	$("#bottom_tags").children().each(function(){
		$(this).css('font-size', '12px');
		$(this).mouseenter(function(){
			var th_txt = $(this).text();
			var th_txt_body = do_txt_body(th_txt);
			
			$("#img").attr('src', img_src);
			$("#text_title").html(th_txt);
			$("#text_body").html(th_txt_body);
		});
	});
}

function do_txt_body(txt){
	switch(txt) {
	  case 'CSS':
		var txt_to_return = "La maîtrise de ce langage me permet de designer ";
		txt_to_return += "des pages web animées et responsives, "; 
		txt_to_return += "tout en respectant la charte graphique des entreprises.";
		img_src = img_base + "css";
		return txt_to_return;
		break;
	  case 'HTML':
		var txt_to_return = "La base d'un site web, ";
		txt_to_return += "mes connaissances en la matière me permettent de créer "; 
		txt_to_return += "des pages web structurées, pouvant facilement évoluer.";
		img_src = img_base + "html";
		return txt_to_return;
		break;
	case 'POO':
	var txt_to_return = "Programmation Orientée Objet, ";
		txt_to_return += "j'ai les compétences nécéssaires pour comprendre la structure en objets "; 
		txt_to_return += "des langages informatiques que j'utilise, comme par exemple le ";
		txt_to_return += "Document Modèle Objet avec Javascript, et sa composition par héritage.";
		img_src = img_base + "poo";
		return txt_to_return;
		break;
	case 'Symfony':
		var txt_to_return = "<ul class='check'><li>Installation en version 3 ou 4, sur un serveur de type LAMP ";
		txt_to_return += "auparavant configuré pour (DNS, redirections, droits). </li>"; 
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Utilisation des objets Doctrine pour requêter une base de données.</li> ";
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Création de pages HTML avec le moteur de template Twig.</li>";
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Installation et utlisation des Bundles, comme par exemple EasyAdminBundle pour obtenir ";
		txt_to_return += "une interface d'administration d'un projet.</li></ul>";
		img_src = img_base + "symfo";
		return txt_to_return;
		break;
	case 'Jquery':
		var txt_to_return = "Ce framework Javascript m'offre une plus grande efficacité ";
		txt_to_return += "dans l'écriture de scripts donnants du dynamisme et de la réactivité aux pages web."; 
		img_src = img_base + "jquery";
		return txt_to_return;
		break;
	case 'Bootstrap':
		var txt_to_return = "Cette collection d'outils m'aide à la création de pages web ";
		txt_to_return += "responsives, avec des composants dont le design et les fonctionnalités ";
		txt_to_return += "sont améliorées, comme par exemple un tableau pouvant être trié ou filtré.";
		img_src = img_base + "bootstrap";
		return txt_to_return;
		break;
	case 'Javascript':
		var txt_to_return = "<ul class='check'><li>Me permet de manipuler les éléments d'une page web (DOM) suite ";
		txt_to_return += "aux actions de l'utilisateur.</li>";
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Je sais également créer des objets dans ce langage, ou compléter ceux "; 
		txt_to_return += "déjà existants (utilisation de prototypes).</li></ul>"; 
		img_src = img_base + "js";
		return txt_to_return;
		break;
	case 'UML':
		var txt_to_return = "J'utilise cette méthode de modélisation graphique ";
		txt_to_return += "afin de schématiser la conception aussi bien générale que détaillée d'un projet, "; 
		txt_to_return += "comme par exemple la représentation des cas utilisateurs, ou celle des classes d'objets métiers. "; 
		img_src = img_base + "uml";
		return txt_to_return;
		break;
	case 'GITLAB':
		var txt_to_return = "Cette plateforme permet notamment d’héberger et de gérer la collaboration et le versionning ";
		txt_to_return += "des projets web. J'ai été amené à l'utiliser sur un projet d'équipe lors de ma formation à l'école centrale."; 
		img_src = img_base + "git";
		return txt_to_return;
		break;
	case 'MVC':
		var txt_to_return = "MCV - soit Modèle-Vue-Contrôleur - j'utilise ce pattern de conception afin de structurer mes projets web : </br>";
		txt_to_return += "Celui-ci permet de séparé la conception graphique des pages HTML, des données qu'elles contiennent. "; 
		txt_to_return += "Ceci facilite le travail en équipe, et la gestion du projet en général."; 
		img_src = img_base + "mvc";
		return txt_to_return;
		break;
	case 'VBA':
		var txt_to_return = "Visual Basic pour Applications, le langage de macro de Micosoft Office : </ br>";
		txt_to_return += "je l'utilise depuis plusieurs années régulièrement pour la création "; 
		txt_to_return += "d'outils Excel permettant notamment l'import, la représentation et la manipulation "; 
		txt_to_return += "de données dans des interfaces utilisateurs améliorées (userform)."; 
		img_src = img_base + "vba";
		return txt_to_return;
		break;
	case 'PHP':
		var txt_to_return = "<b>PHP: Hypertext Preprocessor (sigle récursif)</b>";
		txt_to_return += "<ul class='check'><li>j'utilise ce langage depuis plus de 2 ans afin de gérer le dynamisme "; 
		txt_to_return += "des pages web en lien avec une base de données.</li>"; 
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Je connais la création et l'utilisation de classes d'objets métiers, "; 
		txt_to_return += "par exemple pour représenter une base de données et la requêter avec Doctrine.</li></ul>"; 
		img_src = img_base + "php";
		return txt_to_return;
		break;
	case 'Robots':
		var txt_to_return = "Création avec le langage AutoIt de robots graphiques simulant les actions d'un utilisateur. ";
		txt_to_return += "Permet d'économiser le temps et les efforts souvent démoralisants, "; 
		txt_to_return += "des tâches simples (sans analyse) réalisées habituellement par les employés."; 
		img_src = img_base + "autoit";
		return txt_to_return;
		break;
	case 'Materialize':
		var txt_to_return = "Ce framework, tel Bootstrap, permet la création de pages web ";
		txt_to_return += "responsives avec des composants améliorés. Basé sur un ensemble "; 
		txt_to_return += "de régles de design proposé par Google, appelé Material design, "; 
		txt_to_return += "ses animations et transitions, ses effets de profondeur comme l'éclairage et les ombres, "; 
		txt_to_return += "évoquent un design basé sur le papier et l'encre, c'est à dire naturel, simple et élégant. "; 
		txt_to_return += "";
		img_src = img_base + "material";
		return txt_to_return;
		break;
	case 'Linux':
		var txt_to_return = "Les serveurs web utilisent souvent ce système d'exploitation. ";
		txt_to_return += "<ul class='check'><li>Connaissance des fonctions principales en ligne de commande.</li> ";
		txt_to_return += "<hr class='ligne_fine' />"; 
		txt_to_return += "<li>Connections SSH depuis Windows avec Putty par exemple.</li></ul>"; 
		img_src = img_base + "linux";
		return txt_to_return;
		break;
	case 'API':
		var txt_to_return = "Je sais mettre en place une connection à un site web avec une API pour ";
		txt_to_return += "y récupérer des données, en testant cette connection éventuellement avec un logiciel comme Postman."; 
		img_src = img_base + "api";
		return txt_to_return;
		break;
	case 'BDD':
		var txt_to_return = "<ul class='check'><li>Création et exploitation d'une base de données relationelle.</li> ";
		txt_to_return += "<li>Écriture de requêtes complexes et sécurisées notamment avec Doctrine.</li></ul>"; 
		img_src = img_base + "bdd";
		return txt_to_return;
		break;
	case 'AJAX':
		var txt_to_return = "Permet de requêter et de recevoir une réponse d'un serveur sans recharger la page web. ";
		txt_to_return += "Ceci offre dynamisme et réactivité aux pages web que je crées, "; 
		txt_to_return += "avec des composants pouvant se mettre à jour sans réactualisation de la page."; 
		img_src = img_base + "ajax";
		return txt_to_return;
		break;
	}
}

function change_couleur(){
	$("#the_tags").children().each(function(){
		var col = do_color_by_hazard();
		$(this).children().each(function(){
			$(this).css('color', col);
		});
	});
	
	var t = setTimeout(function(){ 
		change_couleur(); 
	}, 1000);
}

function do_color_by_hazard(){
	var rouge = entierAleatoire(0, 255);
	var vert = entierAleatoire(0, 255);
	var bleu = entierAleatoire(0, 255);
	
	return "rgb(" + rouge + ", " + vert + ", " + bleu + ")";
}


function entierAleatoire(min, max) {
	return Math.random() * (max - min) + min;
}