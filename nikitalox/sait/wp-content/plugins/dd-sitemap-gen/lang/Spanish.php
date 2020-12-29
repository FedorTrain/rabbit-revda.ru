<?php 

/* 
Dagon Design Sitemap Generator v3.x
http://www.dagondesign.com/articles/sitemap-generator-plugin-for-wordpress/

Spanish Language File
*/


// Text shown on sitemap page 

define('DDSG_PAGE_HEADER', '<h2>PC!ginas</h2>');
define('DDSG_POST_HEADER', '<h2>Entradas</h2>');
define('DDSG_CAT_HEADER', '<strong>CategorC-a:</strong>');
define('DDSG_NO_TITLE', '(Sin tC-tulo)');
define('DDSG_VIEW_XML', 'Mapa del sitio en XML');
define('DDSG_CREDITS', 'Plugin escrito por');

// Navigation method 1
// Example: Page 2 of 5 : Previous : Next

define('DDSG_NAV1_PAGE', 'PC!gina');
define('DDSG_NAV1_OF', 'de');
define('DDSG_NAV1_PREV', 'Anterior');
define('DDSG_NAV1_NEXT', 'Siguiente');

// Navigation method 2
// Example: Pages: 1 2 3 4 5

define('DDSG_NAV2_PAGE', 'PC!ginas:');


// Text shown in options page 
 
define('DDSG_DEFAULTS_LOADED', 'B!Opciones por defecto cargadas!');
define('DDSG_CONFIG_UPDATED', 'B!ConfiguraciC3n actualizada!');

define('DDSG_FOR_INFO', 'Para mC!s informaciC3n y actualizaciones, visitar:');
define('DDSG_DEFAULT_NOTICE', '<strong>B?Actualizando?</strong> Si estC!s actualizando desde una versiC3n anterior, pincha en el botC3n <strong>Cargar opciones por defecto</strong> que hay debajo. Algunos ajustes puede que cambien.');

define('DDSG_LANGUAGE', 'Lenguaje');
define('DDSG_LANGUAGE_DESC', 'Los archivos de lenguaje se guardan en <strong>/wp-content/plugins/dd-sitemap-gen/lang/</strong>');
define('DDSG_GENERAL_OPTIONS', 'Opciones generales');
define('DDSG_ITEMS_PER_PAGE', 'Elementos por pC!gina:');
define('DDSG_ITEMS_PER_PAGE_INFO', 'Poner a 0 para ilimitados');
define('DDSG_SITEMAP_SLUG', 'Breve descripciC3n del mapa del sitio:');
define('DDSG_SITEMAP_SLUG_INFO', 'Si se utilizan enlaces permantentes, escribir una breve descripciC3n para el mapa del sitio');
define('DDSG_SITEMAP_GENERATION', 'GeneraciC3n del mapa del sitio');
define('DDSG_SHOW', 'QuC) mostrar:');
define('DDSG_SHOW_BOTH', 'PC!ginas y entradas');
define('DDSG_SHOW_POSTS', 'SC3lo entradas');
define('DDSG_SHOW_PAGES', 'SC3lo pC!ginas');
define('DDSG_WHICH_FIRST', 'Si se muestran ambas, cuC!l primero:');
define('DDSG_WHICH_FIRST_POSTS', 'Entradas primero');
define('DDSG_WHICH_FIRST_PAGES', 'PC!ginas primero');
define('DDSG_POST_SORT', 'OrdenaciC3n de las entradas:');
define('DDSG_POST_SORT_T', 'Por tC-tulo');
define('DDSG_POST_SORT_DA', 'Por fecha (antiguas primero)');
define('DDSG_POST_SORT_DD', 'Por fecha (recientes primero)');
define('DDSG_PAGE_SORT', 'OrdenaciC3n de las pC!ginas:');
define('DDSG_PAGE_SORT_T', 'Por tC-tulo');
define('DDSG_PAGE_SORT_DA', 'Por fecha (antiguas primero)');
define('DDSG_PAGE_SORT_DD', 'Por fecha (recientes primero)');
define('DDSG_PAGE_SORT_MA', 'SegC:n el orden en el menC: (ascendiendo)');
define('DDSG_PAGE_SORT_MD', 'SegC:n el orden en el menC: (descendiendo)');
define('DDSG_POST_COMMENTS', 'Mostrar en nC:mero de comentarios despuC)s de las entradas:');
define('DDSG_PAGE_COMMENTS', 'Mostrar en nC:mero de comentarios despuC)s de las pC!ginas:');
define('DDSG_ZERO_COMMENTS', 'Si se muestra el nC:mero de comentarios, mostrar 0 si no hay:');
define('DDSG_MULTI_POSTS', 'Mostrar las entradas que pertenecen a varias categorC-as sC3lo en una categorC-a:');
define('DDSG_POST_DATES', 'Mostrar la fecha despuC)s de las entradas:');
define('DDSG_PAGE_DATES', 'Mostrar la fecha despuC)s de las pC!ginas:');
define('DDSG_DATE_FORMAT', 'Formato de fecha (si se muestra la fecha):');
define('DDSG_DATE_FORMAT_DESC', 'Utilizar el formato <a href="http://us3.php.net/date" target="_blank">PHP date()</a>');
define('DDSG_EXCLUSIONS', 'Exclusiones');
define('DDSG_EXCLUDED_CATS', 'CategorC-as excluC-das:');
define('DDSG_EXCLUDED_CATS_DESC', '- IDs de categorC-a, separados <br />- Las subcategorC-as tambiC)n serC!n excluidas');
define('DDSG_EXCLUDED_PAGES', 'PC!ginas excluC-das:');
define('DDSG_EXCLUDED_PAGES_DESC', '- IDs de pC!gina, separados por comas<br />- Las subpC!ginas tambiC)n serC!n excluidas');
define('DDSG_HIDE_FUTURE', 'Ocultar entradas con fecha en el futuro');
define('DDSG_HIDE_PASS', 'Ocultar elementos protegidos por contraseC1a:');
define('DDSG_NAVIGATION', 'Navegador');
define('DDSG_NAV_METHOD', 'Forma de mostrar el navegador del mapa del sitio:');
define('DDSG_NAV_WHERE', 'Mostrar navegador del mapa del sitio:');
define('DDSG_NAV_WHERE_TOP', 'Al principio');
define('DDSG_NAV_WHERE_BOT', 'Al final');
define('DDSG_NAV_WHERE_BOTH', 'Al principio y al final');
define('DDSG_MISC', 'MiscelC!nea');
define('DDSG_XML_PATH', 'Ruta completa al mapa del sitio en XML:');
define('DDSG_XML_PATH_DESC', 'Si estC!s usando tambiC)n un plugin para generar un mapa del sitio en XML, puedes escribir aquC- la ruta al fichero XML y se aC1adirC! un enlace a la pC!gina con el mapa del sitio');
define('DDSG_XML_WHERE', 'Donde mostrar el enlace al mapa del sitio en XML:');
define('DDSG_XML_WHERE_LAST', 'Al final de la C:ltima pC!gina');
define('DDSG_XML_WHERE_EVERY', 'Al final de cada pC!gina');
define('DDSG_NEW_WINDOW', 'Los enlaces del mapa se abren en una nueva ventana:');

define('DDSG_DEFAULT_BUTTON', 'Cargar opciones por defecto');
define('DDSG_UPDATE_BUTTON', 'Actualizar opciones');

?>