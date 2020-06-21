@extends('layouts.base')

@section('title', 'Mapa testeo 2')
@section('username', 'David')
@section('stylesheet')
	<!-- Leaflet CSS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	      crossorigin=""/>
@endsection


@section('content')
	<h2>Mapa de incidentes</h2>

	<div id="mapid" style="height: 600px; cursor: crosshair;"></div>

	<button class="btn-download-points">
		Descargar puntos
	</button>

	<button class="btn-set-lugar">
		Establecer nombre
	</button>

@endsection

@section('scripts')
	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
	        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
	        crossorigin=""></script>

	<script>
        const mymap = L.map('mapid');
        mymap.setView([37.18,-3.6], 14);
        const incidentsLayerGroup = L.layerGroup().addTo(mymap);

        const arrIncidents = [[37.19101368910451, -3.625002037562127, 'Chana, Granada (Granada)'],
            [37.191748715157786, -3.6242299301261998, 'Chana, Granada (Granada)'],
            [37.19189401015855, -3.6239296661233587, 'Chana, Granada (Granada)'],
            [37.192184599321294, -3.6245730889866277, 'Chana, Granada (Granada)'],
            [37.190919673628095, -3.6237366392643615, 'Chana, Granada (Granada)'],
            [37.190543610551856, -3.6241119692679384, 'Chana, Granada (Granada)'],
            [37.19187691664356, -3.626771972846641, 'Chana, Granada (Granada)'],
            [37.19200511791165, -3.6271365791358416, 'Chana, Granada (Granada)'],
            [37.18719314774747, -3.6243800979793326, 'Chana, Granada (Granada)'],
            [37.188389758671036, -3.6278760288697853, 'Chana, Granada (Granada)'],
            [37.190150451696866, -3.620894890803295, 'Chana, Granada (Granada)'],
            [37.190069682965564, -3.624182876594369, 'Chana, Granada (Granada)'],
            [37.194713532439856, -3.6257596115798045, 'Chana, Granada (Granada)'],
            [37.193961442205406, -3.624794477284881, 'Chana, Granada (Granada)'],
            [37.192398267109844, -3.628627841253074, 'Chana, Granada (Granada)'],
            [37.19428706406281, -3.6315239867801923, 'Chana, Granada (Granada)'],
            [37.19165940135348, -3.6236904487342207, 'Chana, Granada (Granada)'],
            [37.191355989944405, -3.6238673900216227, 'Chana, Granada (Granada)'],
            [37.19234698689576, -3.623694958243007, 'Chana, Granada (Granada)'],
            [37.195047697453646, -3.623405310399437, 'Chana, Granada (Granada)'],
            [37.19756029710701, -3.6243489972655683, 'Chana, Granada (Granada)'],
            [37.20096157429208, -3.6262571577309437, 'Chana, Granada (Granada)'],
            [37.20053428658055, -3.626525250590656, 'Chana, Granada (Granada)'],
            [37.20267070095031, -3.626589592876975, 'Chana, Granada (Granada)'],
            [37.20284161148758, -3.6275225560287288, 'Chana, Granada (Granada)'],
            [37.201893053119235, -3.627168673453925, 'Chana, Granada (Granada)'],
            [37.20184177935432, -3.625367089436756, 'Chana, Granada (Granada)'],
            [37.2019272356098, -3.6265788691625978, 'Chana, Granada (Granada)'],
            [37.20813024672017, -3.6133848344232575, 'Norte, Granada (Granada)'],
            [37.20688267832589, -3.6131489127067344, 'Norte, Granada (Granada)'],
            [37.2050711039403, -3.613320492136938, 'Norte, Granada (Granada)'],
            [37.20710484954936, -3.61767432017841, 'Norte, Granada (Granada)'],
            [37.209105215659825, -3.6179121617019465, 'Norte, Granada (Granada)'],
            [37.20835326881216, -3.618148083418489, 'Norte, Granada (Granada)'],
            [37.208789057102706, -3.6184697948501237, 'Norte, Granada (Granada)'],
            [37.21001095986844, -3.61692557997827, 'Norte, Granada (Granada)'],
            [37.20896849860848, -3.617225843981132, 'Norte, Granada (Granada)'],
            [37.209447007204446, -3.6160247879696854, 'Norte, Granada (Granada)'],
            [37.208208005489396, -3.616185643685513, 'Norte, Granada (Granada)'],
            [37.20896849860848, -3.615456431107132, 'Norte, Granada (Granada)'],
            [37.20685789770101, -3.6078506543368594, 'Norte, Granada (Granada)'],
            [37.20620847016124, -3.607743417192975, 'Norte, Granada (Granada)'],
            [37.20546503966964, -3.607389534618171, 'Norte, Granada (Granada)'],
            [37.20484978131073, -3.606735388040526, 'Norte, Granada (Granada)'],
            [37.204730147158465, -3.6063600580369486, 'Norte, Granada (Granada)'],
            [37.204362698219406, -3.606006175462145, 'Norte, Granada (Granada)'],
            [37.20422597257608, -3.60568446403051, 'Norte, Granada (Granada)'],
            [37.204730147158465, -3.60568446403051, 'Norte, Granada (Granada)'],
            [37.20590084463914, -3.605695187744887, 'Norte, Granada (Granada)'],
            [37.20674681180756, -3.6057595300312255, 'Norte, Granada (Granada)'],
            [37.20656736501879, -3.606402952894495, 'Norte, Granada (Granada)'],
            [37.20525140886346, -3.6050303174528446, 'Norte, Granada (Granada)'],
            [37.2045848768615, -3.604976698880902, 'Norte, Granada (Granada)'],
            [37.20418324576174, -3.604772948307549, 'Norte, Granada (Granada)'],
            [37.20534540649269, -3.6044297894471216, 'Norte, Granada (Granada)'],
            [37.20590084463914, -3.6045477503054033, 'Norte, Granada (Granada)'],
            [37.20622556043123, -3.6048372905938684, 'Norte, Granada (Granada)'],
            [37.20731078465144, -3.60412952544426, 'Norte, Granada (Granada)'],
            [37.20651609442936, -3.6040651831579416, 'Norte, Granada (Granada)'],
            [37.20537104218944, -3.6039686697284528, 'Norte, Granada (Granada)'],
            [37.20449942361503, -3.6041188017298835, 'Norte, Granada (Granada)'],
            [37.20592648014719, -3.60647801889521, 'Norte, Granada (Granada)'],
            [37.20731932966211, -3.6070034809002176, 'Norte, Granada (Granada)'],
            [37.207498774662774, -3.6074002583325675, 'Norte, Granada (Granada)'],
            [37.20724242453119, -3.608150918339722, 'Norte, Granada (Granada)'],
            [37.20580684770172, -3.6078399306224633, 'Norte, Granada (Granada)'],
            [37.20484123602042, -3.6072179551879677, 'Norte, Granada (Granada)'],
            [37.20428579007553, -3.60723940261674, 'Norte, Granada (Granada)'],
            [37.204020883646706, -3.6061134126060295, 'Norte, Granada (Granada)'],
            [37.20924107793204, -3.6056436340265323, 'Norte, Granada (Granada)'],
            [37.20995883709534, -3.6072092963271594, 'Norte, Granada (Granada)'],
            [37.21071076794364, -3.607745482046563, 'Norte, Granada (Granada)'],
            [37.21108673055786, -3.6060940300308246, 'Norte, Granada (Granada)'],
            [37.209770853212476, -3.603713365436725, 'Norte, Granada (Granada)'],
            [37.20871129893219, -3.6083674574810596, 'Norte, Granada (Granada)'],
            [37.20874547833474, -3.6095899609212783, 'Norte, Granada (Granada)'],
            [37.20558381808415, -3.601159728317703, 'Norte, Granada (Granada)'],
            [37.20202892837565, -3.6030899969075296, 'Norte, Granada (Granada)'],
            [37.20541291375654, -3.5983930100056494, 'Norte, Granada (Granada)'],
            [37.20084962509334, -3.6003232785954573, 'Norte, Granada (Granada)'],
            [37.19982412900123, -3.601888940896084, 'Norte, Granada (Granada)'],
            [37.20120854543413, -3.604333947776522, 'Norte, Granada (Granada)'],
            [37.20365257665977, -3.6001088043077067, 'Norte, Granada (Granada)'],
            [37.20751501008963, -3.600430515739342, 'Norte, Granada (Granada)'],
            [37.20460965823275, -3.594488205360442, 'Norte, Granada (Granada)'],
            [37.20358421322586, -3.5950243910798467, 'Norte, Granada (Granada)'],
            [37.206199070458915, -3.5933729390641083, 'Norte, Granada (Granada)'],
            [37.20643833391676, -3.5948742590784164, 'Norte, Granada (Granada)'],
            [37.203156940360955, -3.592772411058385, 'Norte, Granada (Granada)'],
            [37.19912336532543, -3.5983953882736723, 'Norte, Granada (Granada)'],
            [37.19828585874057, -3.5995112783690995, 'Norte, Granada (Granada)'],
            [37.19876443506968, -3.5967000937056053, 'Norte, Granada (Granada)'],
            [37.19505538916065, -3.594489772939669, 'Beiro, Granada (Granada)'],
            [37.190953006349154, -3.5975155518522732, 'Beiro, Granada (Granada)'],
            [37.189807718016276, -3.5996400349185618, 'Beiro, Granada (Granada)'],
            [37.18989318789464, -3.5988889550466445, 'Beiro, Granada (Granada)'],
            [37.192713639608726, -3.5999833857171546, 'Beiro, Granada (Granada)'],
            [37.192611079574064, -3.6004984119150434, 'Beiro, Granada (Granada)'],
            [37.193072598632995, -3.59884603619683, 'Beiro, Granada (Granada)'],
            [37.19703812761598, -3.600133601691554, 'Beiro, Granada (Granada)'],
            [37.19601257974391, -3.6013567889115454, 'Beiro, Granada (Granada)'],
            [37.1876658109627, -3.6175411949734713, 'Beiro, Granada (Granada)'],
            [37.19087950297823, -3.6093866135068713, 'Beiro, Granada (Granada)'],
            [37.1901273745527, -3.6093866135068713, 'Beiro, Granada (Granada)'],
            [37.18131152939551, -3.6117426405062463, 'Beiro, Granada (Granada)'],
            [37.182644998485856, -3.612386423253598, 'Beiro, Granada (Granada)'],
            [37.178832581991344, -3.6098971299638, 'Beiro, Granada (Granada)'],
            [37.17869581011141, -3.610991560634309, 'Ronda, Granada (Granada)'],
            [37.177054528237264, -3.6120430724550046, 'Ronda, Granada (Granada)'],
            [37.18242275527226, -3.614146096096396, 'Ronda, Granada (Granada)'],
            [37.18247404222574, -3.61622766031287, 'Ronda, Granada (Granada)'],
            [37.18088413047456, -3.6171933344339173, 'Ronda, Granada (Granada)'],
            [37.17187228853193, -3.607867329118895, 'Ronda, Granada (Granada)'],
            [37.16901087674545, -3.6076504966508147, 'Ronda, Granada (Granada)'],
            [37.16880569216197, -3.6078758206124033, 'Ronda, Granada (Granada)'],
            [37.16857485883965, -3.608068955436597, 'Ronda, Granada (Granada)'],
            [37.16847226602563, -3.607693415500648, 'Ronda, Granada (Granada)'],
            [37.16954093434083, -3.6071676595903006, 'Ronda, Granada (Granada)'],
            [37.170250521753125, -3.607371524126953, 'Ronda, Granada (Granada)'],
            [37.17090880773176, -3.607800712625194, 'Ronda, Granada (Granada)'],
            [37.1677626619154, -3.607178389302759, 'Ronda, Granada (Granada)'],
            [37.168164486747884, -3.6061483369069607, 'Ronda, Granada (Granada)'],
            [37.16889973516522, -3.6065775254052017, 'Ronda, Granada (Granada)'],
            [37.17724344848782, -3.6090560337695874, 'Ronda, Granada (Granada)'],
            [37.17617488910873, -3.608401521309757, 'Ronda, Granada (Granada)'],
            [37.17566197523836, -3.608068900223622, 'Ronda, Granada (Granada)'],
            [37.17485840317368, -3.6075002254634603, 'Ronda, Granada (Granada)'],
            [37.1741488590328, -3.607339279776623, 'Ronda, Granada (Granada)'],
            [37.17400352989263, -3.607028118115386, 'Ronda, Granada (Granada)'],
            [37.17518325248433, -3.6083156836101087, 'Ronda, Granada (Granada)'],
            [37.176482635759484, -3.6090560337695874, 'Ronda, Granada (Granada)'],
            [37.17665360557934, -3.6082513053353775, 'Ronda, Granada (Granada)'],
            [37.17703828625938, -3.607972332811516, 'Ronda, Granada (Granada)'],
            [37.17742296498066, -3.6082727647602946, 'Ronda, Granada (Granada)'],
            [37.1760894037055, -3.6073929283388955, 'Ronda, Granada (Granada)'],
            [37.1757902040324, -3.606963739840655, 'Ronda, Granada (Granada)'],
            [37.17714941586895, -3.60736073920152, 'Ronda, Granada (Granada)'],
            [37.176439893244066, -3.6099787890408, 'Ronda, Granada (Granada)'],
            [37.17720070640282, -3.6101397347276376, 'Ronda, Granada (Granada)'],
            [37.17515760653691, -3.608508818434322, 'Ronda, Granada (Granada)'],
            [37.1767390903442, -3.6086697641211596, 'Ronda, Granada (Granada)'],
            [37.176585217697834, -3.6085839264215123, 'Ronda, Granada (Granada)'],
            [37.17651682975442, -3.608895088082749, 'Ronda, Granada (Granada)'],
            [37.1764313447381, -3.6091096823318596, 'Centro, Granada (Granada)'],
            [37.1758158497651, -3.5993461452956437, 'Centro, Granada (Granada)'],
            [37.175482454561084, -3.5988847676600275, 'Centro, Granada (Granada)'],
            [37.17514050923377, -3.599989928042995, 'Centro, Granada (Granada)'],
            [37.17578165545292, -3.5998719012059914, 'Centro, Granada (Granada)'],
            [37.176696347973866, -3.6008375753270387, 'Centro, Granada (Granada)'],
            [37.17693570493623, -3.6012453044003627, 'Centro, Granada (Granada)'],
            [37.17675618728554, -3.6012453044003627, 'Centro, Granada (Granada)'],
            [37.17732038417983, -3.6002903599917735, 'Centro, Granada (Granada)'],
            [37.17645699025314, -3.601610114623872, 'Centro, Granada (Granada)'],
            [37.176097952250174, -3.601234574687904, 'Centro, Granada (Granada)'],
            [37.177063931568405, -3.598992064784592, 'Centro, Granada (Granada)'],
            [37.17759393267247, -3.599496361270023, 'Centro, Granada (Granada)'],
            [37.17780764174317, -3.6012989529626354, 'Centro, Granada (Granada)'],
            [37.17840602392483, -3.6007410079149316, 'Centro, Granada (Granada)'],
            [37.178576989391885, -3.6011058181384423, 'Centro, Granada (Granada)'],
            [37.178081188471474, -3.6003654679789636, 'Centro, Granada (Granada)'],
            [37.17677328422303, -3.598477038586703, 'Centro, Granada (Granada)'],
            [37.17651682975442, -3.597951282676355, 'Centro, Granada (Granada)'],
            [37.176362956655375, -3.5981229580756517, 'Centro, Granada (Granada)'],
            [37.1757902040324, -3.598273174050031, 'Centro, Granada (Granada)'],
            [37.17561923225867, -3.598391200887055, 'Centro, Granada (Granada)'],
            [37.17537132249946, -3.598605795136166, 'Centro, Granada (Granada)'],
            [37.174841305802865, -3.5993032264458096, 'Centro, Granada (Granada)'],
            [37.17467033188177, -3.5996573069568614, 'Centro, Granada (Granada)'],
            [37.17452500374461, -3.600065036030205, 'Centro, Granada (Granada)'],
            [37.17490969526297, -3.6001616034423116, 'Centro, Granada (Granada)'],
            [37.17542261424052, -3.600966331876501, 'Centro, Granada (Granada)'],
            [37.17736227130483, -3.599532695726986, 'Centro, Granada (Granada)'],
            [37.1819252699119, -3.602416302160561, 'Centro, Granada (Granada)'],
            [37.17799314056989, -3.603703867655284, 'Centro, Granada (Granada)'],
            [37.174676315819, -3.603231760307209, 'Centro, Granada (Granada)'],
            [37.173171728749246, -3.602802571808968, 'Centro, Granada (Granada)'],
            [37.1867820219946, -3.605390668260198, 'Centro, Granada (Granada)'],
            [37.18932908490964, -3.603673914267214, 'Beiro, Granada (Granada)'],
            [37.187602561709454, -3.6086739602717475, 'Beiro, Granada (Granada)'],
            [37.1849870602866, -3.6105409302390905, 'Beiro, Granada (Granada)'],
            [37.18406392051093, -3.608459366022617, 'Beiro, Granada (Granada)'],
            [37.18428615889553, -3.6017856391207297, 'Beiro, Granada (Granada)'],
            [37.18391006278465, -3.60200023336986, 'Beiro, Granada (Granada)'],
            [37.183499773982355, -3.6017641796958326, 'Beiro, Granada (Granada)'],
            [37.18652560150536, -3.6013349911975916, 'Beiro, Granada (Granada)'],
            [37.18425196841739, -3.6024508812930183, 'Beiro, Granada (Granada)'],
            [37.18519220092668, -3.6003478576516277, 'Beiro, Granada (Granada)'],
            [37.1837057734826, -3.5993567777280986, 'Albaicín, Granada (Granada)'],
            [37.184090418250975, -3.599109994341592, 'Albaicín, Granada (Granada)'],
            [37.1837143211654, -3.598680805843351, 'Albaicín, Granada (Granada)'],
            [37.18356046272697, -3.599506993702478, 'Albaicín, Granada (Granada)'],
            [37.18129443348511, -3.5981160494160878, 'Albaicín, Granada (Granada)'],
            [37.17951643767653, -3.5942533529318985, 'Albaicín, Granada (Granada)'],
            [37.1805422095966, -3.594682541430139, 'Albaicín, Granada (Granada)'],
            [37.181123474168224, -3.592515139514017, 'Albaicín, Granada (Granada)'],
            [37.18016609484327, -3.5928370308877127, 'Albaicín, Granada (Granada)'],
            [37.179465148714876, -3.593330597660685, 'Albaicín, Granada (Granada)'],
            [37.178781292565375, -3.5950044328038344, 'Albaicín, Granada (Granada)'],
            [37.17949934135986, -3.597171834719957, 'Albaicín, Granada (Granada)'],
            [37.1703009619983, -3.6014546893845734, 'Centro, Granada (Granada)'],
            [37.16958282577877, -3.6014117705347597, 'Centro, Granada (Granada)'],
            [37.16896727501589, -3.6037723072750754, 'Centro, Granada (Granada)'],
            [37.169856402286165, -3.59842891047197, 'Centro, Granada (Granada)'],
            [37.17156623301783, -3.599780854241424, 'Centro, Granada (Granada)'],
            [37.16693250209171, -3.600231502164582, 'Centro, Granada (Granada)'],
            [37.16872789280957, -3.5953172938597167, 'Centro, Granada (Granada)'],
            [37.16927505102407, -3.5939009718155117, 'Centro, Granada (Granada)'],
            [37.16871079405152, -3.5908322740530934, 'Centro, Granada (Granada)'],
            [37.1732341356696, -3.5921420929879275, 'Centro, Granada (Granada)'],
            [37.17287508235097, -3.5919167690263585, 'Centro, Granada (Granada)'],
            [37.17250747837618, -3.5924210655117887, 'Centro, Granada (Granada)'],
            [37.172892180166755, -3.595715087235796, 'Centro, Granada (Granada)'],
            [37.17348205244145, -3.5948030616770414, 'Centro, Granada (Granada)'],
            [37.171815010217294, -3.594695764552476, 'Centro, Granada (Granada)'],
            [37.171404655721176, -3.5939554143930184, 'Centro, Granada (Granada)'],
            [37.17431128538807, -3.593858846980911, 'Centro, Granada (Granada)'],
            [37.17321703793122, -3.596369599695626, 'Centro, Granada (Granada)'],
            [37.17154998986009, -3.596155005446496, 'Centro, Granada (Granada)'],
            [37.17110543749618, -3.5951356827631757, 'Centro, Granada (Granada)'],
            [37.17137045941258, -3.591283715991445, 'Centro, Granada (Granada)'],
            [37.17418219915572, -3.5973771585186403, 'Centro, Granada (Granada)'],
            [37.17372056465373, -3.5971625642695297, 'Centro, Granada (Granada)'],
            [37.152738864825785, -3.603793675554761, 'Zaidín, Granada (Granada)'],
            [37.1526362505171, -3.6032571899319548, 'Zaidín, Granada (Granada)'],
            [37.1537307959637, -3.6031498928073895, 'Zaidín, Granada (Granada)'],
            [37.154551694650785, -3.602720704309149, 'Zaidín, Granada (Granada)'],
            [37.1545345926857, -3.6018837867375835, 'Zaidín, Granada (Granada)'],
            [37.15460300052279, -3.603450324756168, 'Zaidín, Granada (Granada)'],
            [37.15215738190193, -3.6044159988772155, 'Zaidín, Granada (Granada)'],
            [37.15154168922626, -3.6029138391333624, 'Zaidín, Granada (Granada)'],
            [37.15220868939862, -3.6009610314663703, 'Zaidín, Granada (Granada)'],
            [37.154038634015784, -3.6009824908912873, 'Zaidín, Granada (Granada)'],
            [37.155680415792965, -3.6011327068656667, 'Zaidín, Granada (Granada)'],
            [37.157200742228746, -3.598406066340623, 'Zaidín, Granada (Granada)'],
            [37.15853463684807, -3.599436118736401, 'Zaidín, Granada (Granada)'],
            [37.15761117384912, -3.597247257395362, 'Zaidín, Granada (Granada)'],
            [37.159458088566616, -3.59806271554203, 'Zaidín, Granada (Granada)'],
            [37.158177223907586, -3.6066474986212036, 'Zaidín, Granada (Granada)'],
            [37.15489373313996, -3.6088149005373253, 'Zaidín, Granada (Granada)'],
            [37.15451749071674, -3.6087934411124083, 'Zaidín, Granada (Granada)'],
            [37.154979242520476, -3.607269821943658, 'Zaidín, Granada (Granada)'],
            [37.158502144927276, -3.5916478811871437, 'Zaidín, Granada (Granada)'],
            [37.15826272957449, -3.5923775016341635, 'Zaidín, Granada (Granada)'],
            [37.15985311735014, -3.591690800036978, 'Zaidín, Granada (Granada)'],
            [37.16004122551946, -3.5898238300696144, 'Zaidín, Granada (Granada)'],
            [37.15686042442488, -3.588407508025429, 'Zaidín, Granada (Granada)'],
            [37.154945038779886, -3.5894804792710215, 'Zaidín, Granada (Granada)'],
            [37.15386761303047, -3.588815237098753, 'Zaidín, Granada (Granada)'],
            [37.155372584425294, -3.5865619974829825, 'Zaidín, Granada (Granada)'],
            [37.15544099150419, -3.593622148279073, 'Zaidín, Granada (Granada)'],
            [37.15665520685535, -3.5945663629752027, 'Zaidín, Granada (Granada)'],
            [37.15419255257183, -3.5926993930078392, 'Zaidín, Granada (Granada)'],
            [37.15622766846305, -3.592205826234867, 'Zaidín, Granada (Granada)'],
            [37.158502144927276, -3.593686526553803, 'Zaidín, Granada (Granada)'],
            [37.15985311735014, -3.593600688854156, 'Zaidín, Granada (Granada)'],
            [37.201259819629186, -3.6115607095868767, 'Norte, Granada (Granada)'],
            [37.196815926759285, -3.614758163898777, 'Beiro, Granada (Granada)'],
            [37.19676464954563, -3.6111100616637186, 'Norte, Granada (Granada)'],
            [37.202163948775926, -3.615614898528257, 'Beiro, Granada (Granada)'],
            [37.16106213137925, -3.5837670717077046, 'Genil, Granada (Granada)'],
            [37.1644137531971, -3.594325108764454, 'Zaidín, Granada (Granada)'],
            [37.19900884893706, -3.606593961351646, 'Norte, Granada (Granada)'],
            [37.194222964610816, -3.6160361083129677, 'Beiro, Granada (Granada)'],
            [37.184650285797204, -3.6237615012813267, 'Chana, Granada (Granada)'],
            [37.14211241348998, -3.627714739961437, 'Armilla (Granada)'],
            [37.13855453633547, -3.626169661367769, 'Armilla (Granada)'],
            [37.13698080652734, -3.625826310569176, 'Armilla (Granada)'],
            [37.14053875769461, -3.6230795041804336, 'Armilla (Granada)'],
            [37.142591345712425, -3.6243670696751566, 'Armilla (Granada)'],
            [37.13752819452138, -3.6234228549790264, 'Armilla (Granada)'],
            [37.139991391483406, -3.6192168076962443, 'Armilla (Granada)'],
            [37.14156505866554, -3.6187876191980033, 'Armilla (Granada)'],
            [37.1451912105789, -3.619560158494837, 'Armilla (Granada)'],
            [37.14697001399771, -3.61750005370328, 'Armilla (Granada)'],
            [37.147038428678336, -3.6259979859684726, 'Armilla (Granada)'],
            [37.14587537069098, -3.6293456562547526, 'Armilla (Granada)'],
            [37.14614903300298, -3.6257404728695284, 'Armilla (Granada)'],
            [37.14669635465634, -3.6238520434772674, 'Armilla (Granada)'],
            [37.14457546118669, -3.620676048590264, 'Armilla (Granada)'],
            [37.14567012330716, -3.626942200664603, 'Armilla (Granada)'],
            [37.144780711541074, -3.619045132296948, 'Armilla (Granada)'],
            [37.14669635465634, -3.646169845385841, 'Churriana de la Vega (Granada)'],
            [37.1479962277088, -3.647714923979509, 'Churriana de la Vega (Granada)'],
            [37.14614903300298, -3.649603353371789, 'Churriana de la Vega (Granada)']
        ];
        const centers = [[37.183775, -3.599175], [37.179925, -3.593775], [37.1816, -3.5987], [37.1795, -3.5972], [37.145157142857144, -3.6262714285714286], [37.14275714285714, -3.6206857142857145], [37.139716666666665, -3.6250999999999998], [37.18902, -3.6004733333333334], [37.185344444444446, -3.610888888888889], [37.19773333333333, -3.615466666666667], [37.17629393939394, -3.599878787878788], [37.172225, -3.5945187499999998], [37.170071428571426, -3.6011285714285712], [37.1868, -3.6054], [37.1764, -3.6091], [37.192075, -3.62455], [37.2018, -3.626585714285714], [37.1943, -3.6315], [37.1902, -3.6209], [37.14693333333334, -3.6478333333333333], [37.205885714285714, -3.605682857142857], [37.20787, -3.61289], [37.205133333333336, -3.5967444444444445], [37.2003, -3.601211111111111], [37.176286363636365, -3.608590909090909], [37.16930909090909, -3.607418181818182], [37.18204, -3.61514], [37.1851, -3.6281], [37.15807857142857, -3.5939071428571427], [37.15376428571429, -3.6038285714285716], [37.1644, -3.5943]] ;
        const otherArr = [];

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
        }).addTo(mymap);

        /*arrIncidents.forEach(value =>  {
            L.marker([value[0], value[1]]).addTo(incidentsLayerGroup);
        });*/

        const redIcon = new L.Icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        /*centers.forEach(value =>  {
            L.marker([value[0], value[1]], {icon: redIcon}).addTo(incidentsLayerGroup);
        });*/
        mymap.on('click', function (e) {
            let latLng = mymap.mouseEventToLatLng(e.originalEvent);

            // console.log(latLng);
            arrIncidents.push(latLng);
            // console.log(arrIncidents);

            L.marker(latLng).addTo(incidentsLayerGroup);
        });

        $('.btn-download-points').on('click', function (e) {
            // console.log(arrIncidents);
            console.log(otherArr);
        });

        function wait() {
            setTimeout(() => {}, 5000);
        }

        $('.btn-set-lugar').on('click', function (ev) {
            arrIncidents.forEach(function (e) {
                wait();
                let placeName = '';
                let nombreLugar = '';
                /*$.ajax({
	                url: 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
	                    e.lat + '&lon=' + e.lng,
	                success: function(data){
	                    placeName = ((typeof(data.address.locality)!== 'undefined')?data.address.locality +", ":"")+
	                        ((typeof(data.address.city_district)!== 'undefined')?data.address.city_district +", ":"")+
	                        ((typeof(data.address.village)!== 'undefined')?data.address.village:"")+
	                        ((typeof(data.address.town)!== 'undefined')?data.address.town:"")+
	                        ((typeof(data.address.city)!== 'undefined')?data.address.city:"")+
	                        ((typeof(data.address.county)!== 'undefined')?" ("+data.address.county +")":"");

	                    // console.log(placeName);
	                    nombreLugar = (placeName !== "") ? placeName : data.address.country;
                        // console.log(nombreLugar);
                        let obj = {'lat': e.lat, 'lng': e.lng, 'name': nombreLugar}
                        otherArr.push(obj);
	                }
	            });*/
            });
        });
	</script>
@endsection
