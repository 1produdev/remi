enedis = {};
enedis.couleurs = {};
enedis.couleurs.secondaire = {}
enedis.couleurs.secondaire.bleu = {};
enedis.couleurs.secondaire.marron = {};

enedis.couleurs.secondaire.bleu.Clair = "rgb(64, 192, 255)";
enedis.couleurs.secondaire.marron.Median = "rgb(64, 64, 12)";

date_jour = new Date();
anneeEnCours = date_jour.getFullYear();
annee_nPlusUn = anneeEnCours + 1;
annee_nPlusDeux = anneeEnCours + 2;
annee_nPlusTrois = anneeEnCours + 3;

dats_uo = {};

habilitations = [];
habilitations['rip'] = [];
habilitations['rip']['uos'] = [];
habilitations['rip']['uos'][0] = 'UO1';
habilitations['rip']['uos'][1] = 'UO2';
habilitations['rip']['uos'][2] = 'UO3';
habilitations['rip']['uos'][3] = 'UO4';

habilitations['rip_aerien'] = [];
habilitations['rip_souterrain'] = [];
habilitations['cdc'] = [];
habilitations['cdc_aerien_hta'] = [];
habilitations['cdc_aerien_bt'] = [];
habilitations['cdc_poste_et_souterrain'] = [];
habilitations['cdc_premier_troncon'] = [];
habilitations['pers_separation_hta_sout'] = [];
habilitations['pers_separation_bt_sout'] = [];
habilitations['pers_separation_hta_aerien'] = [];
habilitations['pers_separation_bt_aerien'] = [];
habilitations['pers_requisition_hta_sout'] = [];
habilitations['pers_requisition_bt_sout'] = [];
habilitations['pers_manoeuvre_hta'] = [];
habilitations['pers_manoeuvre_bt'] = [];
habilitations['pers_manoeuvre_ps'] = [];
habilitations['pers_manoeuvre_ips'] = [];
habilitations['qualif_cable_hta_synth_aerien'] = [];
habilitations['qualif_cable_synth_bt'] = [];
habilitations['qualif_cable_hta_papier'] = [];
habilitations['qualif_cable_synth_bt_pap'] = [];
habilitations['divers_ge_ht_non_coupable'] = [];
habilitations['divers_ge_ht_coupable'] = [];
habilitations['divers_demarrage_ge_non_coupable'] = [];
habilitations['divers_demarrage_ge_coupable'] = [];
habilitations['divers_amiante'] = [];
habilitations['divers_rd800'] = [];
habilitations['divers_idc'] = [];
habilitations['divers_mesure_terre'] = [];
habilitations['divers_linky'] = [];
habilitations['divers_ge_ht_non_coupable'] = [];
habilitations['divers_grip_linky'] = [];

habilitations['rip_aerien']['uos'] = [];
habilitations['rip_souterrain']['uos'] = [];
habilitations['cdc']['uos'] = [];
habilitations['cdc_aerien_hta']['uos'] = [];
habilitations['cdc_aerien_bt']['uos'] = [];
habilitations['cdc_poste_et_souterrain']['uos'] = [];
habilitations['cdc_premier_troncon']['uos'] = [];
habilitations['pers_separation_hta_sout']['uos'] = [];
habilitations['pers_separation_bt_sout']['uos'] = [];
habilitations['pers_separation_hta_aerien']['uos'] = [];
habilitations['pers_separation_bt_aerien']['uos'] = [];
habilitations['pers_requisition_hta_sout']['uos'] = [];
habilitations['pers_requisition_bt_sout']['uos'] = [];
habilitations['pers_manoeuvre_hta']['uos'] = [];
habilitations['pers_manoeuvre_bt']['uos'] = [];
habilitations['pers_manoeuvre_ps']['uos'] = [];
habilitations['pers_manoeuvre_ips']['uos'] = [];
habilitations['qualif_cable_hta_synth_aerien']['uos'] = [];
habilitations['qualif_cable_synth_bt']['uos'] = [];
habilitations['qualif_cable_hta_papier']['uos'] = [];
habilitations['qualif_cable_synth_bt_pap']['uos'] = [];
habilitations['divers_ge_ht_non_coupable']['uos'] = [];
habilitations['divers_ge_ht_coupable']['uos'] = [];
habilitations['divers_demarrage_ge_non_coupable']['uos'] = [];
habilitations['divers_demarrage_ge_coupable']['uos'] = [];
habilitations['divers_amiante']['uos'] = [];
habilitations['divers_rd800']['uos'] = [];
habilitations['divers_idc']['uos'] = [];
habilitations['divers_mesure_terre']['uos'] = [];
habilitations['divers_linky']['uos'] = [];
habilitations['divers_ge_ht_non_coupable']['uos'] = [];
habilitations['divers_grip_linky']['uos'] = [];

habilitations['rip_aerien']['etoiles'] = [];
habilitations['rip_souterrain']['etoiles'] = [];
habilitations['cdc']['etoiles'] = [];
habilitations['cdc_aerien_hta']['etoiles'] = [];
habilitations['cdc_aerien_bt']['etoiles'] = [];
habilitations['cdc_poste_et_souterrain']['etoiles'] = [];
habilitations['cdc_premier_troncon']['etoiles'] = [];
habilitations['pers_separation_hta_sout']['etoiles'] = [];
habilitations['pers_separation_bt_sout']['etoiles'] = [];
habilitations['pers_separation_hta_aerien']['etoiles'] = [];
habilitations['pers_separation_bt_aerien']['etoiles'] = [];
habilitations['pers_requisition_hta_sout']['etoiles'] = [];
habilitations['pers_requisition_bt_sout']['etoiles'] = [];
habilitations['pers_manoeuvre_hta']['etoiles'] = [];
habilitations['pers_manoeuvre_bt']['etoiles'] = [];
habilitations['pers_manoeuvre_ps']['etoiles'] = [];
habilitations['pers_manoeuvre_ips']['etoiles'] = [];
habilitations['qualif_cable_hta_synth_aerien']['etoiles'] = [];
habilitations['qualif_cable_synth_bt']['etoiles'] = [];
habilitations['qualif_cable_hta_papier']['etoiles'] = [];
habilitations['qualif_cable_synth_bt_pap']['etoiles'] = [];
habilitations['divers_ge_ht_non_coupable']['etoiles'] = [];
habilitations['divers_ge_ht_coupable']['etoiles'] = [];
habilitations['divers_demarrage_ge_non_coupable']['etoiles'] = [];
habilitations['divers_demarrage_ge_coupable']['etoiles'] = [];
habilitations['divers_amiante']['etoiles'] = [];
habilitations['divers_rd800']['etoiles'] = [];
habilitations['divers_idc']['etoiles'] = [];
habilitations['divers_mesure_terre']['etoiles'] = [];
habilitations['divers_linky']['etoiles'] = [];
habilitations['divers_ge_ht_non_coupable']['etoiles'] = [];
habilitations['divers_grip_linky']['etoiles'] = [];


habilitations['rip_aerien']['uos'][0] = 'UO1';
habilitations['rip_souterrain']['uos'][0] = 'UO1';
habilitations['cdc']['uos'][0] = 'UO12';
habilitations['cdc_aerien_hta']['uos'][0] = 'UO2';
habilitations['cdc_aerien_bt']['uos'][0] = 'UO16';
habilitations['cdc_poste_et_souterrain']['uos'][0] = 'UO2';
habilitations['cdc_premier_troncon']['uos'][0] = 'UO11';
habilitations['pers_separation_hta_sout']['uos'][0] = 'UO3';
habilitations['pers_separation_bt_sout']['uos'][0] = 'UO14';
habilitations['pers_separation_hta_aerien']['uos'][0] = 'UO4';
habilitations['pers_separation_bt_aerien']['uos'][0] = 'UO4';
habilitations['pers_requisition_hta_sout']['uos'][0] = 'UO17';
habilitations['pers_requisition_bt_sout']['uos'][0] = 'UO5';
habilitations['pers_manoeuvre_hta']['uos'][0] = 'UO18';
habilitations['pers_manoeuvre_bt']['uos'][0] = 'UO10';
habilitations['pers_manoeuvre_ps']['uos'][0] = 'UO12';
habilitations['pers_manoeuvre_ips']['uos'][0] = 'UO5';
habilitations['qualif_cable_hta_synth_aerien']['uos'][0] = 'UO6';
habilitations['qualif_cable_synth_bt']['uos'][0] = 'UO6';
habilitations['qualif_cable_hta_papier']['uos'][0] = 'UO6';
habilitations['qualif_cable_synth_bt_pap']['uos'][0] = 'UO6';
habilitations['divers_ge_ht_non_coupable']['uos'][0] = 'UO7';
habilitations['divers_ge_ht_coupable']['uos'][0] = 'UO6';
habilitations['divers_demarrage_ge_non_coupable']['uos'][0] = 'UO7';
habilitations['divers_demarrage_ge_coupable']['uos'][0] = 'UO7';
habilitations['divers_amiante']['uos'][0] = 'UO7';
habilitations['divers_rd800']['uos'][0] = 'UO8';
habilitations['divers_idc']['uos'][0] = 'UO8';
habilitations['divers_mesure_terre']['uos'][0] = 'UO9';
habilitations['divers_linky']['uos'][0] = 'UO9';
habilitations['divers_ge_ht_non_coupable']['uos'][0] = 'UO10';
habilitations['divers_grip_linky']['uos'][0] = 'UO10';

habilitations['rip_aerien']['etoiles'][0] = 1;
habilitations['rip_souterrain']['etoiles'][0] = 1;
habilitations['cdc']['etoiles'][0] = 1;
habilitations['cdc_aerien_hta']['etoiles'][0] = 1;
habilitations['cdc_aerien_bt']['etoiles'][0] = 1;
habilitations['cdc_poste_et_souterrain']['etoiles'][0] = 1;
habilitations['cdc_premier_troncon']['etoiles'][0] = 1;
habilitations['pers_separation_hta_sout']['etoiles'][0] = 1;
habilitations['pers_separation_bt_sout']['etoiles'][0] = 1;
habilitations['pers_separation_hta_aerien']['etoiles'][0] = 1;
habilitations['pers_separation_bt_aerien']['etoiles'][0] = 1;
habilitations['pers_requisition_hta_sout']['etoiles'][0] = 1;
habilitations['pers_requisition_bt_sout']['etoiles'][0] = 1;
habilitations['pers_manoeuvre_hta']['etoiles'][0] = 1;
habilitations['pers_manoeuvre_bt']['etoiles'][0] = 1;
habilitations['pers_manoeuvre_ps']['etoiles'][0] = 1;
habilitations['pers_manoeuvre_ips']['etoiles'][0] = 1;
habilitations['qualif_cable_hta_synth_aerien']['etoiles'][0] = 1;
habilitations['qualif_cable_synth_bt']['etoiles'][0] = 1;
habilitations['qualif_cable_hta_papier']['etoiles'][0] = 1;
habilitations['qualif_cable_synth_bt_pap']['etoiles'][0] = 1;
habilitations['divers_ge_ht_non_coupable']['etoiles'][0] = 1;
habilitations['divers_ge_ht_coupable']['etoiles'][0] = 1;
habilitations['divers_demarrage_ge_non_coupable']['etoiles'][0] = 1;
habilitations['divers_demarrage_ge_coupable']['etoiles'][0] = 1;
habilitations['divers_amiante']['etoiles'][0] = 1;
habilitations['divers_rd800']['etoiles'][0] = 1;
habilitations['divers_idc']['etoiles'][0] = 1;
habilitations['divers_mesure_terre']['etoiles'][0] = 1;
habilitations['divers_linky']['etoiles'][0] = 1;
habilitations['divers_ge_ht_non_coupable']['etoiles'][0] = 1;
habilitations['divers_grip_linky']['etoiles'][0] = 1;








