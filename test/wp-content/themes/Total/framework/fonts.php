<?php
/**
 * Font Awesome specific functions
 *
 * @package		Total
 * @subpackage	Framework
 * @author		Alexander Clarke
 * @copyright	Copyright (c) 2014, Symple Workz LLC
 * @link		http://www.wpexplorer.com
 * @since		Total 1.0.0
 */

/**
 * Returns the URL for the font awesome css file
 *
 * @since Total 1.5.2
 */
if ( ! function_exists( 'wpex_font_awesome_css_url' ) ) {
	function wpex_font_awesome_css_url() {
		return WPEX_CSS_DIR_UIR .'font-awesome.min.css';
	}
}

/**
 * Array of Font Awesome Icons
 * Learn more: http://fortawesome.github.io/Font-Awesome/
 *
 * @since 1.0.0
 * @return array
 */
if ( ! function_exists( 'wpex_get_awesome_icons' ) ) { 
	function wpex_get_awesome_icons() {
		$icons=array('none'=>'','adjust'=>'adjust','anchor'=>'anchor','archive'=>'archive','arrows'=>'arrows','arrows-h'=>'arrows-h','arrows-v'=>'arrows-v','asterisk'=>'asterisk','automobile'=>'automobile','ban'=>'ban','bank'=>'bank','bar-chart-o'=>'bar-chart-o','barcode'=>'barcode','bars'=>'bars','beer'=>'beer','bell'=>'bell','bell-o'=>'bell-o','bolt'=>'bolt','bomb'=>'bomb','book'=>'book','bookmark'=>'bookmark','bookmark-o'=>'bookmark-o','briefcase'=>'briefcase','bug'=>'bug','building'=>'building','building-o'=>'building-o','bullhorn'=>'bullhorn','bullseye'=>'bullseye','cab'=>'cab','calendar'=>'calendar','calendar-o'=>'calendar-o','camera'=>'camera','camera-retro'=>'camera-retro','car'=>'car','caret-square-o-down'=>'caret-square-o-down','caret-square-o-left'=>'caret-square-o-left','caret-square-o-right'=>'caret-square-o-right','caret-square-o-up'=>'caret-square-o-up','certificate'=>'certificate','check'=>'check','check-circle'=>'check-circle','check-circle-o'=>'check-circle-o','check-square'=>'check-square','check-square-o'=>'check-square-o','child'=>'child','circle'=>'circle','circle-o'=>'circle-o','circle-o-notch'=>'circle-o-notch','circle-thin'=>'circle-thin','clock-o'=>'clock-o','cloud'=>'cloud','cloud-download'=>'cloud-download','cloud-upload'=>'cloud-upload','code'=>'code','code-fork'=>'code-fork','coffee'=>'coffee','cog'=>'cog','cogs'=>'cogs','comment'=>'comment','comment-o'=>'comment-o','comments'=>'comments','comments-o'=>'comments-o','compass'=>'compass','credit-card'=>'credit-card','crop'=>'crop','crosshairs'=>'crosshairs','cube'=>'cube','cubes'=>'cubes','cutlery'=>'cutlery','dashboard'=>'dashboard','database'=>'database','desktop'=>'desktop','dot-circle-o'=>'dot-circle-o','download'=>'download','edit'=>'edit','ellipsis-h'=>'ellipsis-h','ellipsis-v'=>'ellipsis-v','envelope'=>'envelope','envelope-o'=>'envelope-o','envelope-square'=>'envelope-square','eraser'=>'eraser','exchange'=>'exchange','exclamation'=>'exclamation','exclamation-circle'=>'exclamation-circle','exclamation-triangle'=>'exclamation-triangle','external-link'=>'external-link','external-link-square'=>'external-link-square','eye'=>'eye','eye-slash'=>'eye-slash','fax'=>'fax','female'=>'female','fighter-jet'=>'fighter-jet','file-archive-o'=>'file-archive-o','file-audio-o'=>'file-audio-o','file-code-o'=>'file-code-o','file-excel-o'=>'file-excel-o','file-image-o'=>'file-image-o','file-movie-o'=>'file-movie-o','file-pdf-o'=>'file-pdf-o','file-photo-o'=>'file-photo-o','file-picture-o'=>'file-picture-o','file-powerpoint-o'=>'file-powerpoint-o','file-sound-o'=>'file-sound-o','file-video-o'=>'file-video-o','file-word-o'=>'file-word-o','file-zip-o'=>'file-zip-o','film'=>'film','filter'=>'filter','fire'=>'fire','fire-extinguisher'=>'fire-extinguisher','flag'=>'flag','flag-checkered'=>'flag-checkered','flag-o'=>'flag-o','flash'=>'flash','flask'=>'flask','folder'=>'folder','folder-o'=>'folder-o','folder-open'=>'folder-open','folder-open-o'=>'folder-open-o','frown-o'=>'frown-o','gamepad'=>'gamepad','gavel'=>'gavel','gear'=>'gear','gears'=>'gears','gift'=>'gift','glass'=>'glass','globe'=>'globe','graduation-cap'=>'graduation-cap','group'=>'group','hdd-o'=>'hdd-o','headphones'=>'headphones','heart'=>'heart','heart-o'=>'heart-o','history'=>'history','home'=>'home','image'=>'image','inbox'=>'inbox','info'=>'info','info-circle'=>'info-circle','institution'=>'institution','key'=>'key','keyboard-o'=>'keyboard-o','language'=>'language','laptop'=>'laptop','leaf'=>'leaf','legal'=>'legal','lemon-o'=>'lemon-o','level-down'=>'level-down','level-up'=>'level-up','life-bouy'=>'life-bouy','life-ring'=>'life-ring','life-saver'=>'life-saver','lightbulb-o'=>'lightbulb-o','location-arrow'=>'location-arrow','lock'=>'lock','magic'=>'magic','magnet'=>'magnet','mail-forward'=>'mail-forward','mail-reply'=>'mail-reply','mail-reply-all'=>'mail-reply-all','male'=>'male','map-marker'=>'map-marker','meh-o'=>'meh-o','microphone'=>'microphone','microphone-slash'=>'microphone-slash','minus'=>'minus','minus-circle'=>'minus-circle','minus-square'=>'minus-square','minus-square-o'=>'minus-square-o','mobile'=>'mobile','mobile-phone'=>'mobile-phone','money'=>'money','moon-o'=>'moon-o','mortar-board'=>'mortar-board','music'=>'music','navicon'=>'navicon','paper-plane'=>'paper-plane','paper-plane-o'=>'paper-plane-o','paw'=>'paw','pencil'=>'pencil','pencil-square'=>'pencil-square','pencil-square-o'=>'pencil-square-o','phone'=>'phone','phone-square'=>'phone-square','photo'=>'photo','picture-o'=>'picture-o','plane'=>'plane','plus'=>'plus','plus-circle'=>'plus-circle','plus-square'=>'plus-square','plus-square-o'=>'plus-square-o','power-off'=>'power-off','print'=>'print','puzzle-piece'=>'puzzle-piece','qrcode'=>'qrcode','question'=>'question','question-circle'=>'question-circle','quote-left'=>'quote-left','quote-right'=>'quote-right','random'=>'random','recycle'=>'recycle','refresh'=>'refresh','reorder'=>'reorder','reply'=>'reply','reply-all'=>'reply-all','retweet'=>'retweet','road'=>'road','rocket'=>'rocket','rss'=>'rss','rss-square'=>'rss-square','search'=>'search','search-minus'=>'search-minus','search-plus'=>'search-plus','send'=>'send','send-o'=>'send-o','share'=>'share','share-alt'=>'share-alt','share-alt-square'=>'share-alt-square','share-square'=>'share-square','share-square-o'=>'share-square-o','shield'=>'shield','shopping-cart'=>'shopping-cart','sign-in'=>'sign-in','sign-out'=>'sign-out','signal'=>'signal','sitemap'=>'sitemap','sliders'=>'sliders','smile-o'=>'smile-o','sort'=>'sort','sort-alpha-asc'=>'sort-alpha-asc','sort-alpha-desc'=>'sort-alpha-desc','sort-amount-asc'=>'sort-amount-asc','sort-amount-desc'=>'sort-amount-desc','sort-asc'=>'sort-asc','sort-desc'=>'sort-desc','sort-down'=>'sort-down','sort-numeric-asc'=>'sort-numeric-asc','sort-numeric-desc'=>'sort-numeric-desc','sort-up'=>'sort-up','space-shuttle'=>'space-shuttle','spinner'=>'spinner','spoon'=>'spoon','square'=>'square','square-o'=>'square-o','star'=>'star','star-half'=>'star-half','star-half-empty'=>'star-half-empty','star-half-full'=>'star-half-full','star-half-o'=>'star-half-o','star-o'=>'star-o','suitcase'=>'suitcase','sun-o'=>'sun-o','support'=>'support','tablet'=>'tablet','tachometer'=>'tachometer','tag'=>'tag','tags'=>'tags','tasks'=>'tasks','taxi'=>'taxi','terminal'=>'terminal','thumb-tack'=>'thumb-tack','thumbs-down'=>'thumbs-down','thumbs-o-down'=>'thumbs-o-down','thumbs-o-up'=>'thumbs-o-up','thumbs-up'=>'thumbs-up','ticket'=>'ticket','times'=>'times','times-circle'=>'times-circle','times-circle-o'=>'times-circle-o','tint'=>'tint','toggle-down'=>'toggle-down','toggle-left'=>'toggle-left','toggle-right'=>'toggle-right','toggle-up'=>'toggle-up','trash-o'=>'trash-o','tree'=>'tree','trophy'=>'trophy','truck'=>'truck','umbrella'=>'umbrella','university'=>'university','unlock'=>'unlock','unlock-alt'=>'unlock-alt','unsorted'=>'unsorted','upload'=>'upload','user'=>'user','users'=>'users','video-camera'=>'video-camera','volume-down'=>'volume-down','volume-off'=>'volume-off','volume-up'=>'volume-up','warning'=>'warning','wheelchair'=>'wheelchair','wrench'=>'wrench','file'=>'file','file-archive-o'=>'file-archive-o','file-audio-o'=>'file-audio-o','file-code-o'=>'file-code-o','file-excel-o'=>'file-excel-o','file-image-o'=>'file-image-o','file-movie-o'=>'file-movie-o','file-o'=>'file-o','file-pdf-o'=>'file-pdf-o','file-photo-o'=>'file-photo-o','file-picture-o'=>'file-picture-o','file-powerpoint-o'=>'file-powerpoint-o','file-sound-o'=>'file-sound-o','file-text'=>'file-text','file-text-o'=>'file-text-o','file-video-o'=>'file-video-o','file-word-o'=>'file-word-o','file-zip-o'=>'file-zip-o','circle-o-notch'=>'circle-o-notch','cog'=>'cog','gear'=>'gear','refresh'=>'refresh','spinner'=>'spinner','check-square'=>'check-square','check-square-o'=>'check-square-o','circle'=>'circle','circle-o'=>'circle-o','dot-circle-o'=>'dot-circle-o','minus-square'=>'minus-square','minus-square-o'=>'minus-square-o','plus-square'=>'plus-square','plus-square-o'=>'plus-square-o','square'=>'square','square-o'=>'square-o','bitcoin'=>'bitcoin','btc'=>'btc','cny'=>'cny','dollar'=>'dollar','eur'=>'eur','euro'=>'euro','gbp'=>'gbp','inr'=>'inr','jpy'=>'jpy','krw'=>'krw','money'=>'money','rmb'=>'rmb','rouble'=>'rouble','rub'=>'rub','ruble'=>'ruble','rupee'=>'rupee','try'=>'try','turkish-lira'=>'turkish-lira','usd'=>'usd','won'=>'won','yen'=>'yen','align-center'=>'align-center','align-justify'=>'align-justify','align-left'=>'align-left','align-right'=>'align-right','bold'=>'bold','chain'=>'chain','chain-broken'=>'chain-broken','clipboard'=>'clipboard','columns'=>'columns','copy'=>'copy','cut'=>'cut','dedent'=>'dedent','eraser'=>'eraser','file'=>'file','file-o'=>'file-o','file-text'=>'file-text','file-text-o'=>'file-text-o','files-o'=>'files-o','floppy-o'=>'floppy-o','font'=>'font','header'=>'header','indent'=>'indent','italic'=>'italic','link'=>'link','list'=>'list','list-alt'=>'list-alt','list-ol'=>'list-ol','list-ul'=>'list-ul','outdent'=>'outdent','paperclip'=>'paperclip','paragraph'=>'paragraph','paste'=>'paste','repeat'=>'repeat','rotate-left'=>'rotate-left','rotate-right'=>'rotate-right','save'=>'save','scissors'=>'scissors','strikethrough'=>'strikethrough','subscript'=>'subscript','superscript'=>'superscript','table'=>'table','text-height'=>'text-height','text-width'=>'text-width','th'=>'th','th-large'=>'th-large','th-list'=>'th-list','underline'=>'underline','undo'=>'undo','unlink'=>'unlink','angle-double-down'=>'angle-double-down','angle-double-left'=>'angle-double-left','angle-double-right'=>'angle-double-right','angle-double-up'=>'angle-double-up','angle-down'=>'angle-down','angle-left'=>'angle-left','angle-right'=>'angle-right','angle-up'=>'angle-up','arrow-circle-down'=>'arrow-circle-down','arrow-circle-left'=>'arrow-circle-left','arrow-circle-o-down'=>'arrow-circle-o-down','arrow-circle-o-left'=>'arrow-circle-o-left','arrow-circle-o-right'=>'arrow-circle-o-right','arrow-circle-o-up'=>'arrow-circle-o-up','arrow-circle-right'=>'arrow-circle-right','arrow-circle-up'=>'arrow-circle-up','arrow-down'=>'arrow-down','arrow-left'=>'arrow-left','arrow-right'=>'arrow-right','arrow-up'=>'arrow-up','arrows'=>'arrows','arrows-alt'=>'arrows-alt','arrows-h'=>'arrows-h','arrows-v'=>'arrows-v','caret-down'=>'caret-down','caret-left'=>'caret-left','caret-right'=>'caret-right','caret-square-o-down'=>'caret-square-o-down','caret-square-o-left'=>'caret-square-o-left','caret-square-o-right'=>'caret-square-o-right','caret-square-o-up'=>'caret-square-o-up','caret-up'=>'caret-up','chevron-circle-down'=>'chevron-circle-down','chevron-circle-left'=>'chevron-circle-left','chevron-circle-right'=>'chevron-circle-right','chevron-circle-up'=>'chevron-circle-up','chevron-down'=>'chevron-down','chevron-left'=>'chevron-left','chevron-right'=>'chevron-right','chevron-up'=>'chevron-up','hand-o-down'=>'hand-o-down','hand-o-left'=>'hand-o-left','hand-o-right'=>'hand-o-right','hand-o-up'=>'hand-o-up','long-arrow-down'=>'long-arrow-down','long-arrow-left'=>'long-arrow-left','long-arrow-right'=>'long-arrow-right','long-arrow-up'=>'long-arrow-up','toggle-down'=>'toggle-down','toggle-left'=>'toggle-left','toggle-right'=>'toggle-right','toggle-up'=>'toggle-up','arrows-alt'=>'arrows-alt','backward'=>'backward','compress'=>'compress','eject'=>'eject','expand'=>'expand','fast-backward'=>'fast-backward','fast-forward'=>'fast-forward','forward'=>'forward','pause'=>'pause','play'=>'play','play-circle'=>'play-circle','play-circle-o'=>'play-circle-o','step-backward'=>'step-backward','step-forward'=>'step-forward','stop'=>'stop','youtube-play'=>'youtube-play','adn'=>'adn','android'=>'android','apple'=>'apple','behance'=>'behance','behance-square'=>'behance-square','bitbucket'=>'bitbucket','bitbucket-square'=>'bitbucket-square','bitcoin'=>'bitcoin','btc'=>'btc','codepen'=>'codepen','css3'=>'css3','delicious'=>'delicious','deviantart'=>'deviantart','digg'=>'digg','dribbble'=>'dribbble','dropbox'=>'dropbox','drupal'=>'drupal','empire'=>'empire','facebook'=>'facebook','facebook-square'=>'facebook-square','flickr'=>'flickr','foursquare'=>'foursquare','ge'=>'ge','git'=>'git','git-square'=>'git-square','github'=>'github','github-alt'=>'github-alt','github-square'=>'github-square','gittip'=>'gittip','google'=>'google','google-plus'=>'google-plus','google-plus-square'=>'google-plus-square','hacker-news'=>'hacker-news','html5'=>'html5','instagram'=>'instagram','joomla'=>'joomla','jsfiddle'=>'jsfiddle','linkedin'=>'linkedin','linkedin-square'=>'linkedin-square','linux'=>'linux','maxcdn'=>'maxcdn','openid'=>'openid','pagelines'=>'pagelines','pied-piper'=>'pied-piper','pied-piper-alt'=>'pied-piper-alt','pied-piper-square'=>'pied-piper-square','pinterest'=>'pinterest','pinterest-square'=>'pinterest-square','qq'=>'qq','ra'=>'ra','rebel'=>'rebel','reddit'=>'reddit','reddit-square'=>'reddit-square','renren'=>'renren','share-alt'=>'share-alt','share-alt-square'=>'share-alt-square','skype'=>'skype','slack'=>'slack','soundcloud'=>'soundcloud','spotify'=>'spotify','stack-exchange'=>'stack-exchange','stack-overflow'=>'stack-overflow','steam'=>'steam','steam-square'=>'steam-square','stumbleupon'=>'stumbleupon','stumbleupon-circle'=>'stumbleupon-circle','tencent-weibo'=>'tencent-weibo','trello'=>'trello','tumblr'=>'tumblr','tumblr-square'=>'tumblr-square','twitter'=>'twitter','twitter-square'=>'twitter-square','vimeo-square'=>'vimeo-square','vine'=>'vine','vk'=>'vk','wechat'=>'wechat','weibo'=>'weibo','weixin'=>'weixin','windows'=>'windows','wordpress'=>'wordpress','xing'=>'xing','xing-square'=>'xing-square','yahoo'=>'yahoo','youtube'=>'youtube','youtube-play'=>'youtube-play','youtube-square'=>'youtube-square','ambulance'=>'ambulance','h-square'=>'h-square','hospital-o'=>'hospital-o','medkit'=>'medkit','plus-square'=>'plus-square','stethoscope'=>'stethoscope','user-md'=>'user-md','wheelchair'=>'wheelchair','angellist'=>'angellist','area-chart'=>'fa-area-chart','at'=>'at','bell-slash'=>'bell-slash','bell-slash-o'=>'bell-slash-o','bicycle'=>'bicycle','binoculars'=>'binoculars','birthday-cake'=>'birthday-cake','bus'=>'bus','calculator'=>'calculator','cc'=>'cc','cc-amex'=>'cc-amex','cc-discover'=>'cc-discover','cc-paypal'=>'cc-paypal','cc-stripe'=>'cc-stripe','cc-visa'=>'cc-visa','copyright'=>'copyright','eyedropper'=>'eyedropper','futbol-o'=>'futbol-o','google-wallet'=>'google-wallet','ils'=>'ils','ioxhost'=>'ioxhost','lastfm'=>'lastfm','lastfm-square'=>'lastfm-square','line-chart'=>'line-chart','meanpath'=>'meanpath','newspaper-o'=>'newspaper-o','paint-brush'=>'paint-brush','paypal'=>'paypal','pie-chart'=>'pie-chart','plug'=>'plug','shekel'=>'shekel','sheqel'=>'sheqel','slideshare'=>'slideshare','soccer-ball-o'=>'soccer-ball-o','toggle-off'=>'toggle-off','toggle-on'=>'toggle-on','trash'=>'trash','tty'=>'tty','twitch'=>'twitch','wifi'=>'wifi','yelp'=>'yelp',);
		return apply_filters( 'wpex_get_awesome_icons', $icons );
	}
}

/**
 * Array of Font Icons for meta options
 *
 * @since 1.0.0
 * @return array
 */
if ( ! function_exists( 'wpex_get_meta_awesome_icons' ) ) { 
	function wpex_get_meta_awesome_icons() {
		$awesome_icons = wpex_get_awesome_icons();
		$return_array = array();
		foreach ( $awesome_icons as $awesome_icon ) {
			$return_array[] = array(
				'name'	=> $awesome_icon,
				'value'	=> $awesome_icon
			);
		}
		return $return_array;
	}
}

/**
 * Font Awesome icons corresponding to post formats
 *
 * @since 1.4.0
 * @return string
 */
if ( ! function_exists( 'wpex_post_format_icon' ) ) {
	function wpex_post_format_icon() {
		global $post;
		$post_id = $post->ID;
		$format = get_post_format();
		// Video
		if ( 'video' == $format ) {
			$icon = 'fa-film';
		}
		// Audio
		if ( 'audio' == $format ) {
			$icon = 'fa-music';
		}
		// Gallery
		elseif ( 'gallery' == $format ) {
			$icon = 'fa-file-photo-o';
		}
		// Quote
		elseif ( 'quote' == $format ) {
			$icon = 'fa-quote-left';
		}
		// Standard
		else {
			$icon = 'fa-file-text-o';
		}
		// Apply filters for child theme editing
		$icon = apply_filters( 'wpex_post_format_icon', $icon );
		// Return correct font awesome classname
		echo $icon;
	}
}

/**
 * List of standard fonts
 *
 * @since Total 1.0.0
 */
if ( ! function_exists( 'wpex_standard_fonts' ) ) {
	function wpex_standard_fonts() {
		return array(
			"Arial, Helvetica, sans-serif",
			"Arial Black, Gadget, sans-serif",
			"Bookman Old Style, serif",
			"Comic Sans MS, cursive",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"Lucida Console, Monaco, monospace",
			"Lucida Sans Unicode, Lucida Grande, sans-serif",
			"MS Sans Serif, Geneva, sans-serif",
			"MS Serif, New York, sans-serif",
			"Palatino Linotype, 'Book Antiqua, Palatino, serif",
			"Tahoma, Geneva, sans-serif",
			"Tahoma,Geneva, sans-serif",
			"Times New Roman, Times, serif",
			"Trebuchet MS, Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif",
			"Tahoma, Geneva",
			"Garamond, serif",
			"Bookman Old Style",
			"Tahoma, Geneva",
			"Verdana",
			"Comic Sans",
			"Courier, monospace",
			"Arial Black",
			"Arial",
			"Comic Sans MS",
			"Courier",
			"Georgia",
			"Paratina Linotype",
			"Trebuchet MS",
			"Times New Roman",
			"Times New Roman, Times,serif",
		);
	}
}

/**
 * List of All GooGle fonts
 *
 * @since Total 1.6.0
 */
if ( ! function_exists( 'wpex_google_fonts_array' ) ) {
	function wpex_google_fonts_array() {
		return array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alegreya Sans', 'Alegreya Sans SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buda', 'Buenard', 'Butcherman', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calligraffitti', 'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clicker Script', 'Coda', 'Coda Caption', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Courgette', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Economica', 'Ek Mukta', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Exo 2', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fira Mono', 'Fira Sans', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Halant', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Henny Penny', 'Herr Von Muellerhoff', 'Hind', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kalam', 'Kameron', 'Kantumruy', 'Karla', 'Karma', 'Kaushan Script', 'Kavoon', 'Kdam Thmor', 'Keania One', 'Kelly Slab', 'Kenia', 'Khand', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Laila', 'Lancelot', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Modern Antiqua', 'Molengo', 'Molle', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Open Sans Condensed', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Puritan', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Rajdhani', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Rozha One', 'Rubik Mono One', 'Rubik One', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sarina', 'Sarpanch', 'Satisfy', 'Scada', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slabo 13px', 'Slabo 27px', 'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Source Serif Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Taprom', 'Tauri', 'Teko', 'Telex', 'Tenor Sans', 'Text Me One', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vesper Libre', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada');
	}
}

/** Fun little function to output the options for use in the Customizer selector
$std_fonts = wpex_standard_fonts();
$g_fonts = wpex_google_fonts_array();
$fonts = array_merge( $std_fonts, $g_fonts );
	foreach ( $fonts as $font ) { ?>
	<option value="<?php echo $font; ?>" if( "<?php echo $font; ?>" == $this_val() ) { echo 'selected="selected"'; } ?>><?php echo $font; ?></option>
<?php } */