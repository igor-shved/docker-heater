<?php require_once('s-check.php');
require_once('./include/functions.php');
$serverName = $_SERVER['SERVER_NAME'];

if (strpos($serverName, "log2.com.ua") === FALSE && strpos($serverName, "ilog2.com") === FALSE)
    require("./include/globalsettings.php");
else
    require("../../include/globalsettings.php");

?>
<html>
    <head><title>Мой Умный Дом - house4u</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

        <script type="text/javascript" src="<?php print $jsPath; ?>include/jquery.min.js"></script>
        <script type="text/javascript" src="<?php print $jsPath; ?>include/js/rotate.js"></script>
        <script type="text/javascript" src="<?php print $jsPath; ?>include/js/md5.js"></script>

        <style>

            div {
                -moz-user-select: none;
                -ms-user-select: none;
                -o-user-select: none;
                -webkit-user-select: none;
                user-select: none;
            }

            @font-face {
                font-family: 'lcd';
                src: url('<?php print $webFontKitPath;?>webfontkit/lcd-webfont.eot');
                src: url('<?php print $webFontKitPath;?>webfontkit/lcd-webfont.eot?#iefix') format('embedded-opentype'),
                url('<?php print $webFontKitPath;?>webfontkit/lcd-webfont.woff') format('woff'),
                url('<?php print $webFontKitPath;?>webfontkit/lcd-webfont.ttf') format('truetype'),
                url('<?php print $webFontKitPath;?>webfontkit/lcd-webfont.svg#webfont') format('svg');
            }

           /* #body-id {
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                margin: auto;
            } */

            #room-name-text {
                font-size: 24px;
            }

            .ui-slider .ui-slider-handle {
                height: 45px;
                width: 25px;
                margin-top: 4px;
            / / add this
            }

            /*left range */
            .ui-slider .ui-slider-range {
                height: 45px;
                background: none;
            }

            /* right range */
            .ui-slider-horizontal {
                height: 45px;
                border: 0;
                background: url('img/temp.png');
                background-size: 100%;
            }

            /*.ui-slider-horizontal { height: 45px; border: 0; background: none;  }*/

            .textarea {
                -moz-appearance: textfield-multiline;
                -webkit-appearance: textarea;
                border: 1px solid gray;
                font: medium -moz-fixed;
                font: -webkit-small-control;
                height: 28px;
                overflow: auto;
                padding: 2px;
                resize: both;
            }

            /*  MODAL DIALOG - START */

            /* The Modal (background) */
            .modal, .modal2 {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 60px; /* Location of the box */
                left: 0;
                top: 0;
                max-width: 410px;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0, 0, 0); /* Fallback color */
                background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            }

            .modal2 {
                background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
            }

            /* Modal Content */
            .modal-content, .modal-content2 {
                position: relative;
                background-color: #5C5C5C;
                margin: auto;
                padding: 0;
                border: 1px solid #888;
                width: 80%;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                -webkit-animation-name: animatetop;
                -webkit-animation-duration: 0.5s;
                animation-name: animatetop;
                animation-duration: 0.5s
            }

            .modal-content2 {
                background-color: #686868;
            }

            .expand-animation {
                -webkit-animation-name: animate_expand;
                -webkit-animation-duration: 0.5s;
                animation-name: animate_expand;
                animation-duration: 0.5s
            }

            /* Add Animation */
            @-webkit-keyframes animatetop {
                from {
                    top: -300px;
                    opacity: 0
                }
                to {
                    top: 0;
                    opacity: 1
                }
            }

            @keyframes animatetop {
                from {
                    top: -300px;
                    opacity: 0
                }
                to {
                    top: 0;
                    opacity: 1
                }
            }

            /* Add Animation */
            @-webkit-keyframes animate_expand {
                from {
                    top: -300px;
                    opacity: 0;
                }
                to {
                    top: 0;
                    opacity: 1
                }
            }

            @keyframes animate_expand {
                from {
                    top: -300px;
                    opacity: 0
                }
                to {
                    top: 0;
                    opacity: 1
                }
            }

            /* The Close Button */
            .close {
                color: white;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }

            .modal-header {
                padding: 2px 16px;
                background-color: #747474;
                color: white;
            }

            .modal-body {
                padding: 2px 16px;
            }

            .modal-footer {
                padding: 2px 16px;
                background-color: #747474;
                color: white;
            }

            /*  MODAL DIALOG - END   */

            .modalButton {
                width: 136px;
                height: 36px;
                border-radius: 5px;
                font-size: large;
                font-weight: bold;
                float: center;
            }

            .bgcolor {
                background: #5C5C5C;
                padding: 2px;
                border: 1px solid silver;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                border-radius: 5px;
                color: white;
                cursor: pointer;
            }

            .icon {
                background-repeat: no-repeat;
                background-position: center center;
                height: 100%;
                width: 50%;
                float: left;
            }

            .ib, .ib2, .ib3, .ib4 {
                height: 60px;
                width: 33%;
                cursor: pointer;
            }

            .ib2 {
                width: 25%
            }

            .ib3 {
                width: 28%
            }

            .ib4 {
                width: 24.5%
            }

            .digit {
                line-height: 60px;
                font-size: 40px;
                text-align: center;
                color: white;
                font-weight: bold;
            }

            .checkbox {
                line-height: 40px;
                padding-left: 40px;
                color: white;
            }

            .scheduleTime {
                line-height: 50px;
                height: 40px;
                text-align: center;
                color: white;
            }

            .roomchange, .button, .btnm, .btnm2 {
                font-family: sans-serif;
                padding: 2px;
                border: 1px solid silver;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                border-radius: 5px;
                line-height: 35px;
                text-align: center;
                font-size: large
            }

            .roomchange, .button, .btnm, .btnm2 {
                width: 270px;
                height: 55px;
                color: white;
                font-weight: bold;
                cursor: pointer
            }

            .button {
                background: #16a500
            }

            .btnm2, .set, .btnc {
                background: -webkit-gradient(linear, left top, right top, color-stop(0%, #3CBEFF), color-stop(100%, #001EE1));
                background: linear-gradient(to right, #3CBEFF, #001EE1)
            }

            .btnm, .set2, .btnc2 {
                color: black;
            }

            .roomchange {
                background: #A4A4A4
            }

            .setShed, .setShed2, .set, .set2 {
                width: 380px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                height: 45px;
                line-height: 45px;
                margin-top: 2px;
            }

            .setShed, .setShed2 {
                width: auto;
                height: auto
            }

            .btnW {
                height: 45
            }

            .brd {
                border: solid 1px silver;
                background: #FFF;
                height: 95px;
                padding: 5px
            }

            .sl, .sl2 {
                height: 45px;
                width: 20%;
                float: left
            }

            .sl2 {
                width: 25%
            }

            .val, .name {
                width: 190px;
                float: left;
                margin-left: 5px
            }

            .val {
                width: 40px;
                text-align: center
            }

            .bigcheck {
                width: 35px;
                height: 35px;
                line-height: 35px;
                float: right;
                margin-right: 25px;
            }

            .myrange, .mytxt, .btn, .btne {
                width: 130px;
                line-height: 45px;
                height: 44px
            }

            .btn {
                height: 45px;
                float: right
            }

            .mytxt {
                background: transparent;
                border: solid 1px silver;
                float: right
            }

            .btnm, .btnm2, .btnc, .btnc2 {
                width: 130px;
                height: 45px;
                float: left;
            }

            .btnm, .btnm2 {
                margin-right: 15px;
                margin-top: 2px
            }

            .btnc, .btnc2 {
                float: right;
                width: 20%;
                height: 100%;

            }

            ;
        </style>

        <?php
        function printArray($name, $arr)
        {
            print "var $name=[";
            $len = count($arr);

            for ($i = 0; $i < $len; $i++) {
                $item = trim($arr[$i]);
                if ($item == "")
                    $item = "0";

                print $item;
                if ($i != $len - 1)
                    print ",";
            }

            print "];\n";
        }

        function printArrayString($name, $arr)
        {
            print "var $name=[";
            $len = count($arr);

            for ($i = 0; $i < $len; $i++) {
                $item = trim($arr[$i]);
                if ($item == "")
                    $item = "'_'";
                else
                    $item = "'$item'";

                print $item;
                if ($i != $len - 1)
                    print ",";
            }
            print "];\n";
        }


        function printArray2($name, $arr)
        {
            $len = count($arr);

            for ($i = 0; $i < $len; $i++) {
                $len2 = count($arr[$i]);

                print "$name" . "[$i]=[";

                for ($j = 0; $j < $len2; $j++) {
                    print $arr[$i][$j];
                    if ($j != $len2 - 1)
                        print ",";
                }
                print "];\n";
            }
        }

        $serverName = $_SERVER['SERVER_NAME'];
        $weAreLocalServer = "false";

        if (strpos($serverName, "log2.com.ua") === FALSE && strpos($serverName, "ilog2.com") === FALSE)
            $weAreLocalServer = "true";

        $requestPass = "false";
        if (file_exists("settings/auth"))
            $requestPass = "true";

        $chatText = htmlspecialchars(file_get_contents("settings/chat"));
        $lastChatModify = htmlspecialchars(filemtime("settings/chat"));

        $rn = explode(";", htmlspecialchars(file_get_contents("outputs/names")));
        //print_r($rn);
        //$modes = explode(";",file_get_contents("settings/mode.x"));

        // last modify time by controller
        $modeXControllerDate = 0;//$modes[1];

        // last modify time by user
        $modeXWebUIDate = htmlspecialchars(file_get_contents("outputs/date.x"));//$modes[2];

        $roomsNum = $rn[0];

        for ($i = 0; $i < 17; $i++)
            $roomsName[$i] = $rn[$i + 1];

        //$roomsName    = ['Весь Дом','Холл','Кухня','CУ этаж 1','Детская','Спальная','Детская 2','Гостевая','Спортзал'];

        $currentMode = array();//[7,1,2,1,3,3,4,5,3,4,1,4,3,2,1,4];
        $rightNowTemp = array();//[22,22,23,24,22,18,22,21,22,22,23,24,22,18,22,21];
        $standByTemp = array();//[18,18,17,20,16,18,18,19,18,18,17,20,16,18,18,19];
        $scheduleIntervalsNum = array();
        $scheduleArrTime = array();
        $scheduleArrTemp = array();
        $scheduleArrIntervalMode = array();
        $updateSettings = array();
        $followAllHouse = array();

        $roomsTsensors = [0, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $roomsPOutputs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

        // 2;1;10e;0;0:af;6;7a1de;dbc90;3f52;472c;5abe;0;78;

        //print "PASSW:".file_get_contents("settings/passw.php");

        $ld = explode(";", htmlspecialchars(file_get_contents("datalog/latestdata.php")));

        $modeXStrPrev = $ld[23];

        $state = 0;
        if (isset($ld[2]))
            $state = $ld[2];

        $st = htmlspecialchars(file_get_contents("outputs/st"));

        if (strlen($st) < 17) {
            $st = "00000000000000000:restore";
            file_put_contents("outputs/st", $st, LOCK_EX);
        }

        $stateDebugStr = $st;

        for ($i = 0; $i < 17; $i++)
            if ($st[$i] == 0)
                $updateSettings[$i] = 0;
            else
                $updateSettings[$i] = 1;

        //$st = file_get_contents("outputs/flags");

        $st = decbin(hexdec($ld[24]));
        $len = strlen($st);
        //print $st;

        for ($i = 0; $i < 16; $i++) {
            if ($i < $len)
                $followAllHouse[16 - $i] = $st[$i];
            else
                $followAllHouse[16 - $i] = 0;
        }

        $followAllHouse[0] = 1;
        //debughtml(['$ld' => $ld, '$len' => $len, '$st' => $st, '$followAllHouse' => $followAllHouse]);
        //print $st;
        //print_r($followAllHouse);

        for ($i = 0; $i < 17; $i++) {
            $parts = explode(":", htmlspecialchars(file_get_contents("outputs/$i")));

            $p1 = explode(";", $parts[0]);
            $p2 = explode(";", $parts[1]);

            $currentMode[$i] = $p1[1];
            $rightNowTemp[$i] = hexdec($p1[2]) / 10.0;

            $roomsPOutputs[$i] = hexdec($p2[0]);
            $roomsTsensors[$i] = hexdec($p2[1]);

            $standByTemp[$i] = hexdec($p2[2]) / 10.0;

            $intervalsNum = hexdec($p2[3]);
            $scheduleIntervalsNum[$i] = $intervalsNum;


            if ($intervalsNum > 10)
                $intervalsNum = 10;

            $j = 4;

            for ($j = 0; $j < $intervalsNum; $j++) {
                $val = hexdec($p2[$j + 4]);

                $time = substr($val, 0, countVal($val) - 4);
                $scheduleArrTime[$i][$j] = $time;

                $temp = substr($val, -3, 3);
                $scheduleArrTemp[$i][$j] = $temp / 10.0;
            }

            $val = hexdec($p2[$j + 4]);

            //print "VAL=".$val."\n";

            $fstr = "%0$intervalsNum" . "d";
            $istr = sprintf($fstr, $val);

            for ($j = $intervalsNum; $j < 6; $j++)
                $istr .= "0";

            for ($j = 0; $j < 6; $j++) {
                //if ( $istr[$j] <= 2 )
                $scheduleArrIntervalMode[$i][$j] = $istr[$j];
                //else
                //  $scheduleArrIntervalMode[$i][$j] = 2;
            }

        }
        ?>

        <script type='text/javascript'>
            /*
function supportWebSocket() {
if (window.WebSocket)
{
alert("HTML5 WebSocket поддерживается вашим браузером.");
}
else
{
alert("HTML5 WebSocket не поддерживается вашим браузером.");
}
}

ws = new WebSocket("ws://house4u.com.ua:789");
    ws.onopen = function() {
        alert("connection success");
        ws.send('tom');
    };
    ws.onmessage = function(e) {
        alert("recv message from server：" + e.data);
    };  */

            var scriptPath = '<?php print $scriptPath; ?>';
            var debug = false;

            var SI = {};
            var Cur = {};
            var Def = {};

            var scheduleArrTime = {};
            var scheduleArrTemp = {};
            var scheduleArrIntervalMode = {};
            //var scheduleArrMode={};

            <?php
            printArray('currentMode', $currentMode);
            printArray('rightNowTemp', $rightNowTemp);
            printArray('standByTemp', $standByTemp);
            printArray('scheduleIntervalsNum', $scheduleIntervalsNum);
            printArrayString('roomsName', $roomsName);

            printArray('roomsTsensors', $roomsTsensors);
            printArray('roomsPOutputs', $roomsPOutputs);

            printArray('updateSettings', $updateSettings);

            printArray('followAllHouse', $followAllHouse);
            printArray('followAllHouse2', $followAllHouse);

            printArray2('scheduleArrTime', $scheduleArrTime);
            printArray2('scheduleArrTemp', $scheduleArrTemp);
            printArray2('scheduleArrIntervalMode', $scheduleArrIntervalMode);
            ?>

            var roomsTsensorsNames = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];

            // if Temp is:
            // 127 - disabled
            // 126 - turn on
            // 125 - turn off
            // else - desired temp

            // if some item is 1 - we use 'all house' settings
            //var followAllHouse = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

            //var updateSettings = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            //var updateSettings2 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

            window.onerror = function () {
                //location.reload(true);
            };

            var switchCtrlArr = [];

            String.prototype.hashCode = function () {
                var hash = 0;
                var len = this.length;
                if (this.length == 0) return hash;
                for (var i = 0; i < len; i++) {
                    var chr = this.charCodeAt(i);
                    hash = ((hash << 5) - hash) + chr;
                    hash &= 0xFFFFFFFF;
                }
                var hash2 = hash >> 24;
                hash2 &= 0xFF;
                hash &= 0x00FFFFFF;

                hash3 = hash.toString(16);

                while (hash3.length < 6)
                    hash3 = '0' + hash3;

                if (hash2 != 0)
                    return hash2.toString(16) + hash3;

                return hash3;
            };

            function gbi(id) {
                return document.getElementById(id)
            }

            function sih(id, val) {
                gbi(id).innerHTML = val;
            }

            function newVal(id) {
                size = gbi('s' + id).value;
                sih('v' + id, size);
            }

            var x = 0;

            function createSlider(id, val) {
                $("#slider-range-min" + id).slider({
                    range: "min",
                    value: 37,
                    min: 10,
                    max: 33,
                    step: 0.1,
                    slide: function (event, ui) {
                        sih('v' + id, ui.value);
                        //$( "#amount0" ).val( "$" + ui.value );
                    }
                });
                //$( "#slider-range-min0" ).css('background', 'rgb(0,255,0)');
                //$( "#slider-range-min"+id+" .ui-slider .ui-slider-range{" ).css('background', 'rgb(255,0,0)');
                //$( "#amount0" ).val( "c" + $( "#slider-range-min" ).slider( "value" ) );
                $("#slider-range-min" + id).slider('value', val);
            }

            function startEngine() {
                fillDesiredTemp();
                setInterval(fillDesiredTemp, 2000);

                clearUpdateStatus();
                setInterval(clearUpdateStatus, 3000);

                updateTSensors();
                updateSettingsFromRemoteHost();
            }

            function applyLogin() {
                var login = document.getElementById('login').value;
                var passw = document.getElementById('passw').value;

                urlstate = scriptPath + 's-login.php';

                $.post(urlstate,
                    {
                        login: login,
                        passw: passw
                    },
                    function (data, status) {
                        var vals = login + passw;

                        var hash = vals.hashCode();
                        if (hash < 0) hash = -hash;
                        hash = hash.toString(16);

                        if (data == hash) {
                            startEngine();
                            document.getElementById('myModal-login').style.display = 'none';
                            //alert("Data: " + data + "\nStatus: " + status);
                        } else
                            document.getElementById('login-error-msg').innerHTML = "<b>Ошибка - пароль или логин</b>";
                    });
            }

            function simpleStart() {
                startEngine();
                digitalWatch();
            }

            function authStart() {
                document.getElementById('myModal-login').style.display = 'block';
                digitalWatch();
            }

            window.mobilecheck = function () {
                var check = false;
                (function (a) {
                    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
                })(navigator.userAgent || navigator.vendor || window.opera);
                return check;
            };

            function createMenu() {
                reloadUI2();

                if (!window.mobilecheck()) {
                    gbi('body-id').style.maxWidth = "500px";
                    $('.modal').css({'max-width': '500px'});
                    $('.modal2').css({'max-width': '500px'});
                }

                <?php
                if ($auth)
                    print "simpleStart();";
                else
                    print "authStart();";
                ?>
            }

            function isNumeric(num) {
                return !isNaN(num)
            }

            var timeId = 0;

            function clear(str) {
                str = str + '';
                if (str[0] == '&')
                    return str.substr(2);
                return str;
            }


            function reset(id) {
                setVals(Cur, id);
            }

            function defs(id) {
                setVals(Def, id);
            }

            function setVals(Vals, id) {
                x = 0;
                for (var j = 0; j < id - 1; j++)
                    x += SI[j].length;

                for (var i = 0; i < SI[id - 1].length; i++) {
                    var type = getType(Cur[id - 1][i]);

                    var curI = clear(Vals[id - 1][i]);
                    var bVal = true;
                    if (curI == 'd' || curI == 'rd')
                        bVal = false;
                    var cur = curI;

                    if (type == 'T')
                        cur = Cur[id - 1][i].substr(2);

                    if ("brtpxo".indexOf(type) >= 0)
                        cur = '-';

                    var z = x + i;
                    var val = gbi('v' + z);
                    var val2 = gbi('s' + z);
                    if (type == 'b' || type == 'o')
                        val2.checked = bVal;
                    else {
                        if (type == 't' || type == 'r')
                            val2.value = curI;
                        else
                            val.innerHTML = cur;
                    }
                }
            }

            function switchControl(id) {
                if (switchCtrlArr[id]) {
                    var obj = gbi('h' + id);
                    obj.style.fontWeight = 'bold';
                    obj = gbi('m' + id);
                    obj.style.fontWeight = 'bold';
                    obj = gbi('t' + id);
                    obj.style.fontWeight = 'normal';

                    obj = gbi('btn' + id);
                    obj.value = 'hh:mm';

                } else {
                    var obj = gbi('h' + id);
                    obj.style.fontWeight = 'normal';
                    obj = gbi('m' + id);
                    obj.style.fontWeight = 'normal';
                    obj = gbi('t' + id);
                    obj.style.fontWeight = 'bold';

                    obj = gbi('btn' + id);
                    obj.value = 't°c';
                }
                switchCtrlArr[id] = !switchCtrlArr[id];
            }

            function showCtrl(id) {
                spoiler(id, 'c');
            }

            function spoiler(id, charx) {
                var ch = 'b';
                if (charx)
                    ch = charx;

                if (id[0] == 's')
                    ch = '';

                //alert(ch+id);
                var block = gbi(ch + id).style;
                if (block.display == 'block') {
                    block.display = 'none';
                    //gbi('v' +id).innerHTML = parseFloat(gbi('tv'+id).value);
                } else {
                    block.display = 'block';
                    //gbi('tv'+id).value = gbi('v' +id).innerHTML;
                }
            }

            function inc(val, id) {
                var textVal = gbi('v' + id);
                var val2 = (parseFloat(textVal.innerHTML) + val).toFixed(1);
                sih('v' + id, val2);
                //gbi('tv' +id).value = val2;
                $("#slider-range-min" + id).slider('value', val2);
            }

            function inc2(val, id) {
                if (switchCtrlArr[id]) {
                    // temperature

                    var textVal = gbi('t' + id);
                    var val2 = (parseFloat(textVal.innerHTML) + val).toFixed(1);
                    if (val2 > 0)
                        val2 = "+" + val2;
                    sih('t' + id, val2 + "&deg;c");
                    //gbi('tv'+id).value = val2;
                } else {
                    // time
                    var val2;
                    if (Math.abs(Math.abs(val) - 1) < 0.01) // inc +-1
                    {
                        var timeObj = gbi('h' + id);
                        val2 = (parseFloat(timeObj.innerHTML) + val).toFixed(0) % 24;
                        if (val2 < 0)
                            val2 = 23;
                        if (val2 < 10)
                            val2 = "0" + val2;
                        sih('h' + id, val2);

                    } else   // inc +- 0.1
                    {

                        var timeObj = gbi('m' + id);
                        //alert(timeObj.innerHTML);
                        val2 = (parseFloat(timeObj.innerHTML) + val * 10).toFixed(0) % 60;
                        if (val2 < 0)
                            val2 = 59;
                        if (val2 < 10)
                            val2 = "0" + val2;
                        sih('m' + id, val2);
                    }

                    //gbi('tv'+id).value = gbi('h'+id).innerHTML+":"+gbi('m'+id).innerHTML;
                }

            }

            function parseDates(data) {
                //return;

                result = "";
                lines = data.split("\n");

                for (i = 0; i < lines.length; i++) {
                    line = lines[i];

                    if (line == "")
                        continue;

                    //alert(line);

                    start = line.indexOf("${d");
                    end = line.indexOf("}");

                    if (end - start == 13) {
                        time = line.substring(3, 13);
                        date = new Date(time * 1000);
                        //alert(time);
                        msg = line.substring(end + 1);

                        result += '<font size="-2">' + getShortDate(date, true) + '</font>' + msg + '\n';
                    }

                }

                return result;
            }

            function parseLogins(data) {
                //return;

                result = "";
                lines = data.split("\n");

                for (i = 0; i < lines.length; i++) {
                    line = lines[i];

                    if (line == "")
                        continue;

                    //alert(line);

                    start = line.indexOf("${u");
                    end = line.indexOf("}");

                    //if ( end - start == 13 )
                    {
                        part1 = line.substring(0, start);
                        part2 = line.substring(start + 3, end);
                        part3 = line.substring(end + 1);

                        //alert(line + "\n"+part1 + "[" + part2 + "]" + part3);
                        result += part1 + "<b>" + part2 + "</b>: " + part3 + '\n';
                    }


                }

                return result;
            }

            var showChatVar = false;
            var firstLoad = true;

            function loadChat() {
                if (!showChatVar)
                    return;

                var urlstate = scriptPath + "s-chat.php?getdate";


                $.ajax({url: urlstate, async: true, cache: false, timeout: 3000})
                    .done(function (data) {
                        //alert(data + " " + lastChatModify );


                        if (data * 1 > lastChatModify || firstLoad) {
                            //alert('подгрузка');
                            if (firstLoad) {
                                firstLoad = false;
                                gbi('all-text').innerHTML = "Загрузка...";
                            }
                            lastChatModify = data * 1;

                            urlstate = scriptPath + "s-chat.php?getfile";
                            $.ajax({url: urlstate, async: true, cache: false, timeout: 3000})
                                .done(function (data) {
                                    data = parseDates(data);
                                    data = parseLogins(data);
                                    data = data.replace(/\n/g, "<br>");
                                    gbi('all-text').innerHTML = data;
                                    gbi('all-text').scrollTop = gbi('all-text').scrollHeight;
                                    //alert(data);
                                })
                                .fail(function (data) {

                                });
                        }
                        setTimeout(loadChat, 3000);
                    })
                    .fail(function (data) {
                        setTimeout(loadChat, 3000);
                    });
            }

            function showChat() {
                showChatVar = !showChatVar;

                if (showChatVar) {
                    gbi('chat').style.display = 'block';
                    gbi('all-text').scrollTop = gbi('all-text').scrollHeight;
                    haveNewMessage = false;
                } else
                    gbi('chat').style.display = 'none';

                if (showChatVar)
                    loadChat();
                // $chat = "id='chatIconId' onclick='showChat()'";
            }


            var firstMessage = true;
            var userName = "user";

            function sendMessage() {
                var message = escapeHtml2(gbi('chat-msg').value);

                if (message == "")
                    return;

                //alert(gbi('chat-msg').value);
                if (firstMessage) {
                    userName = prompt("От чьего имени писать?", 'user');
                    //alert(res);

                    if (userName != "") {
                        firstMessage = false;
                    }
                }

                message = message.replace(/\n/g, " ");

                date = new Date();

                h = date.getHours();
                m = date.getMinutes();

                time = "<font size='-2'>" + h + ":" + m + '</font>';

                var msg = time + ' <b>' + userName + "</b>: " + message + "<br>";

                var msg2 = "${d" + Math.floor(date.getTime() / 1000) + '} ${u' + userName + "}" + message;// + "\n";

                var urlstate = scriptPath + "s-chat.php?msg=" + msg2;

                gbi('chat-msg').style.color = "gray";
                $.ajax({url: urlstate, async: true, cache: false, timeout: 3000})
                    .done(function (data) {
                        gbi('all-text').innerHTML += msg;
                        gbi('all-text').scrollTop = gbi('all-text').scrollHeight;

                        gbi('chat-msg').value = "";
                        gbi('chat-msg').style.color = "black";
                        gbi('chat-msg').focus();

                        lastChatModify = data * 1;
                        //alert(data);

                    })
                    .fail(function (data) {
                    });
            }
        </script>
    </head>
    <body onload='createMenu()' id='body-id' style='max-width: 500px; font-family: Tahoma;'>

        <div id='debugId' style='display:none; font-size: small'>debug info...</div>
        <div id='chat' style='display:none; word-break: break-all;'>

            <div class='textarea' id='all-text' style="width:98%; height: 120px"></div>
            <div>
                <div style='float: left; width:80%'><textarea id='chat-msg' style="width:100%" rows='3'></textarea>
                </div>
                <div style='width:20%; background: gray; float: left; height: 48px; line-height: 48px;text-align: center; color: white; font-weight: bold'
                     onclick='sendMessage()'>>>
                </div>
            </div>
            <div style='clear:both'></div>
        </div>

        <?php
        for ($i = 0; $i < 17; $i++)   // rooms num + all house
            printBlock($i);
        ?>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span onclick='closeModal("")' class="close">X</span>
                    <div id='roomNameStr'>--</div>
                    <div style='height: 40px; line-height: 40px;'><b>Нагрев: </b><span id='modeTypeStr'>--</span></div>

                </div>
                <div class="modal-body">
                    <div id='ib1' class='icon ib' onclick='selectMode(1)'
                         style='background-image: url("<?php print $iconsPath; ?>/mode-hand.png")'></div>
                    <div id='ib2' class='icon ib' onclick='selectMode(2)'
                         style='background-image: url("<?php print $iconsPath; ?>/mode-schedule.png")'></div>
                    <div id='ib3' class='icon ib' onclick='selectMode(3)'
                         style='background-image: url("<?php print $iconsPath; ?>/mode-standby.png")'></div>
                    <div id='ib7' onclick='selectMode(7)'></div>

                    <div id='selmodeid1' class='icon ib' style='height: 4px'></div>
                    <div id='selmodeid2' class='icon ib' style='height: 4px'></div>
                    <div id='selmodeid3' class='icon ib' style='height: 4px'></div>
                    <div id='selmodeid7' class='icon ib' style='height: 4px; float:left'></div>


                    <div class='icon ib' onclick='selectMode(4)'
                         style='background-image: url("<?php print $iconsPath; ?>/mode-on.png")'></div>
                    <div class='icon ib' onclick='selectMode(5)'
                         style='background-image: url("<?php print $iconsPath; ?>/mode-off.png")'></div>
                    <div id='advancedSettingsId' class='icon ib' onclick='selectMode(6)'
                         style='background-image: url("<?php print $iconsPath; ?>/settings.png")'></div>

                    <div id='selmodeid4' class='icon ib' style='height: 4px; float:left' onclick='selectMode(4)'></div>
                    <div id='selmodeid5' class='icon ib' style='height: 4px; float:left' onclick='selectMode(5)'></div>
                    <div id='selmodeid6' class='icon ib' style='height: 4px; float:left' onclick='selectMode(6)'></div>

                    <div style='clear:both'></div>

                    <div class='expand-animation' id='additionalRoomSettingsId' style='display:none'>
                        <div style='width:100%; height:2px; background: gray'></div>

                        <div id='asid1' class='icon ib' onclick='selectMode(10)'
                             style='width:33%;background-image: url("<?php print $iconsPath; ?>/settings-ex.png")'></div>
                        <div id='asid2' class='icon ib' onclick='selectMode(8)'
                             style='width:33%;background-image: url("<?php print $iconsPath; ?>/t1.png")'></div>
                        <div id='asid4' class='icon ib' onclick='selectTurnOffTime()'
                             style='width:33%;background-image: url("<?php print $iconsPath; ?>/clock.png")'></div>
                        <div id='asid3' class='icon ib' onclick='selectMode(9)'
                             style='width:33%;background-image: url("<?php print $iconsPath; ?>/schedule-settings.png")'></div>
                    </div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">

                    <center>
                        <button class='modalButton' onclick='applyRoomMode(true)'>OK</button>
                    </center>
                </div>
            </div>

        </div>

        <div id="myModal2" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">

                    <div style='width:80%;height: 20px; float: left'><span id='roomNameStr3'>--</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='closeModal(2)'
                                                                           class="close">X</span></div>

                    <div class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/schedule-settings-small.png")'></div>
                    <div class='icon' style='height: 40px; line-height: 40px;'><b>Расписание</b><span
                                id='scheduleNumId'></span></div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">

                    <?php
                    for ($i = 1; $i < 7; $i++)
                        printScheduleSection($i);
                    ?>

                    <div class='expand-animation' id='addScheduleId' style='padding-top:10px;height: 40px; width: 100%'>
                        <div style='height: 40px; width:45%; float: left' onclick='addScedule()'>
                            <div class='icon'
                                 style='width: 35%; background-image: url("<?php print $iconsPath; ?>/add-btn.png")'></div>
                            <div class='icon' style='width: 5%; line-height:40px; color: white'></div>
                            <div class='icon' style='width: 50%; line-height:40px; color: white'>Добавить</div>
                        </div>
                        <div style='height: 40px; width: 10%; float: left'></div>
                        <div onclick='deleteSchedule(1)' style='height: 40px; width: 40%; float: left'>
                            <div class='icon'
                                 style='height: 40px; width: 36%; background-image: url("<?php print $iconsPath; ?>/del-btn.png")'></div>
                            <div class='icon' style='width: 5%; line-height:40px; color: white'></div>
                            <div class='icon' style='width: 50%; line-height:40px; color: white'>Удалить</div>
                        </div>
                    </div>

                    <div class='expand-animation' id='copyPasteId' style='height: 40px; width: 100%'>
                        <div style='height: 40px; width:45%; float: left' onclick='copySchedule()'>
                            <div class='icon'
                                 style='width: 35%; background-image: url("<?php print $iconsPath; ?>/copy.png")'></div>
                            <div class='icon' style='width: 5%; line-height:40px; color: white'></div>
                            <div class='icon' style='width: 50%; line-height:40px; color: white'>Копиров</div>
                        </div>
                        <div style='height: 40px; width: 10%; float: left'></div>
                        <div onclick='pasteSchedule()' style='height: 40px; width: 40%; float: left'>
                            <div class='icon' id='pasteButtonId'
                                 style='line-height: 40px;height: 40px; width: 36%;color: gray; background-image: url("<?php print $iconsPath; ?>/paste.png")'></div>
                            <div class='icon' style='width: 5%; line-height:40px; color: white'></div>
                            <div class='icon' style='width: 50%; line-height:40px; color: white'>Вставить</div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class='icon' id='deleteButtonBottomId' onclick='deleteSchedule(2)'
                         style='height:40px;width:20%;float:left;background-image: url("<?php print $iconsPath; ?>/empty20.png")'></div>
                    <div style='height:40px;float: left; align: center'>
                        <button class='modalButton' onclick='applyScheduleSettings()'>OK</button>
                    </div>
                    <div class='icon' id='advancedSettingsScheduleId' onclick='advancedSettingsScheduleClick()'
                         style='height:40px;width:20%;background-image: url("<?php print $iconsPath; ?>/3-dots.png")'></div>
                    <div style='clear:both'></div>
                </div>
            </div>

        </div>

        <div id="myModal3" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span onclick='closeModal(3)' class="close">X</span>
                    <div id='roomNameStr2'>--</div>
                    <div style='height: 40px; line-height: 40px;'><b>Включить ручной нагрев</b><span
                                id='currentTemp'>--</span></div>
                </div>
                <div class="modal-body">
                    <div class='icon ib3' style='width: 16%'></div>
                    <div class='icon ib3' onclick='addTemp(10,0,0,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,1,0,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,0,0.5,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                    <div class='icon ib3'
                         style='width: 16%;background-image: url("<?php print $iconsPath; ?>/t1.png");'></div>
                    <div class='icon ib3 digit' id='digit10'>2</div>
                    <div class='icon ib3 digit' id='digit1'>2</div>
                    <div class='icon ib3 digit' id='digit05'>.5</div>

                    <div class='icon ib3' style='width: 16%' onclick='selectMode(4)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(-10,0,0,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,-1,0,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,0,-0.5,"")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>

                    <div class='icon ib3' id='setTimeOutId' onclick='setTimeOut()'
                         style='width: 15%;background-image: url("<?php print $iconsPath; ?>/check-false.png")'></div>
                    <div class='digit' style='font-size: medium'>Отключить через...</div>

                    <div class='expand-animation' id='timoutSettingsId' style='display:none'>
                        <div class='icon ib3' style='width: 16%'></div>
                        <div class='icon ib3' onclick='addTimeOut(10,0,0,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,1,0,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,0,1,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                        <div class='icon ib3'
                             style='width: 16%;background-image: url("<?php print $iconsPath; ?>/clock.png");'></div>
                        <div class='icon ib3 digit' id='time10'>2</div>
                        <div class='icon ib3 digit' id='time1'>2</div>
                        <div class='icon ib3 digit' style='font-size: medium' id='time05'>часов</div>

                        <div class='icon ib3' style='width: 16%' onclick='selectMode(4)'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(-10,0,0,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,-1,0,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,0,-1,"")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    </div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyRightNowMode()'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myModal-timeout" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span onclick="closeModal('-timeout')" class="close">X</span>
                    <div id='selectedModeId-timeout'>Режим: --</div>
                    <div style='height: 40px; line-height: 40px;'><b>Отключить через...</b></div>
                </div>
                <div class="modal-body">

                    <div class='expand-animation'>
                        <div class='icon ib3' style='width: 16%'></div>
                        <div class='icon ib3' onclick='addTimeOut(10,0,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,1,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,0,1,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                        <div class='icon ib3'
                             style='width: 16%;background-image: url("<?php print $iconsPath; ?>/clock-enabled.png");'></div>
                        <div class='icon ib3 digit' id='_time10'>2</div>
                        <div class='icon ib3 digit' id='_time1'>2</div>
                        <div class='icon ib3 digit' style='font-size: medium' id='_time05'>часов</div>

                        <div class='icon ib3' style='width: 16%' onclick='selectMode(4)'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(-10,0,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,-1,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTimeOut(0,0,-1,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>

                        <div id='timeOutDateId' style='color:white'></div>
                    </div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyTimeOutMode()'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myModal6" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span onclick='closeModal(6)' class="close">X</span>
                    <div id='roomNameStr6'>--</div>
                    <div style='height: 40px; line-height: 40px;'><b>Настройки</b></div>
                </div>
                <div class="modal-body">
                    <div style='color: white'>Никого нет дома:</div>
                    <div class='icon ib3' style='width: 16%'></div>
                    <div class='icon ib3' onclick='addTemp(10,0,0,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,1,0,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,0,0.5,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                    <div class='icon ib3'
                         style='width: 16%;background-image: url("<?php print $iconsPath; ?>/t1.png");'></div>
                    <div class='icon ib3 digit' id='__digit10'>2</div>
                    <div class='icon ib3 digit' id='__digit1'>2</div>
                    <div class='icon ib3 digit' id='__digit05'>.5</div>

                    <div class='icon ib3' style='width: 16%' onclick='selectMode(4)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(-10,0,0,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,-1,0,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib3' onclick='addTemp(0,0,-0.5,"__")'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyRoomAdvancedSettings()'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myModal8" class="modal">
            <!-- Modal content -->
            <div class="modal-content" id="myModal8c">
                <div class="modal-header">

                    <div style='width:80%;height: 20px; float: left'><span id='roomNameStr7'>--</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='closeModal(8)'
                                                                           class="close">X</span></div>

                    <div class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/s_settings-ex.png")'></div>
                    <div class='icon' style='height: 40px; line-height: 40px;'>Доп настройки</div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">

                    <div class='expand-animation'>

                        <div id='roomTsensorId' style='display:none'>
                            <div style='color: white'>Датчик температуры</div>
                            <div class='icon ib3'
                                 style='width: 16%;background-image: url("<?php print $iconsPath; ?>/t2.png");'></div>
                            <div class='icon ib3 digit' style='width: 16%'>T</div>
                            <div class='icon ib3 digit' style='width: 20%' id='digitT'>2</div>
                            <div class='icon ib3' onclick='shiftTs(-1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/left.png")'></div>
                            <div class='icon ib3' onclick='shiftTs(1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/right.png")'></div>
                            <hr>
                        </div>

                        <div id='roomPOutputId' style='display:none'>
                            <div style='color: white'>Номер выхода (реле)</div>
                            <div class='icon ib3'
                                 style='width: 16%;background-image: url("<?php print $iconsPath; ?>/relay-s.png");'></div>
                            <div class='icon ib3 digit' style='width: 16%'>P</div>
                            <div class='icon ib3 digit' style='width: 20%' id='digitP'>2</div>
                            <div class='icon ib3' onclick='shiftPs(-1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/left.png")'></div>
                            <div class='icon ib3' onclick='shiftPs(1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/right.png")'></div>
                            <hr>
                        </div>
                        <div id='roomsNumId'>
                            <div style='color: white'>Число комнат:</div>
                            <div class='icon ib3'
                                 style='width: 16%;background-image: url("<?php print $iconsPath; ?>/rooms-num.png");'></div>
                            <div class='icon ib3 digit' style='width: 6%'></div>
                            <div class='icon ib3 digit' style='width: 30%' id='digitN'>2</div>
                            <div class='icon ib3' onclick='shiftN(-1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/left.png")'></div>
                            <div class='icon ib3' onclick='shiftN(1)'
                                 style='width: 24%; background-image: url("<?php print $iconsPath; ?>/right.png")'></div>
                        </div>
                        <p>
                        <div style='color: white'>Название:</div>
                        <input id='room-name-text' type='text' style='width:100%; height:40px; background: #D4D4D4'
                               onfocus='focusFunction()' onblur='blurFunction()'></p>
                        <div id='roomsNumId2'>
                            <div style='color: white'>Сменить пароль:</div>
                            <input id='room-passw-text' type='text' style='width:100%; height:40px; background: #D4D4D4'
                                   onfocus='focusFunction()' onblur='blurFunction()'></p>
                            <div class='icon ib3' id='requestPassId' onclick='requestPass()'
                                 style='height: 40px;width: 15%;background-image: url("<?php print $iconsPath; ?>/check-false.png")'></div>
                            <div class='checkbox'>Запрашивать пароль</div>
                            <div class='icon ib3' id='useRmoteServerId' onclick='useRmoteServer()'
                                 style='height: 40px;width: 15%;background-image: url("<?php print $iconsPath; ?>/check-false.png")'></div>
                            <div class='checkbox'>Использовать ilog2.com</div>
                            <div class='icon ib3' id='showHideDebugId' onclick='showHideDebug()'
                                 style='height: 40px;width: 15%;background-image: url("<?php print $iconsPath; ?>/check-false.png")'></div>
                            <div class='checkbox' style='font-size: medium;line-height: 40px;'>Показать окно отладки
                            </div>

                            <div class='icon ib3' onclick='resetRooms()'
                                 style='height: 40px;width: 15%;background-image: url("<?php print $iconsPath; ?>/settings.png")'></div>
                            <div class='checkbox' style='font-size: medium;line-height: 40px;'>Сброс настроек...</div>
                        </div>
                    </div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <div style='float:left'>
                        <button class='modalButton' onclick='applyRoomNum()'>OK</button>
                    </div>
                    <div class='icon ib3' onclick='showInfo()'
                         style='float: right;height: 40px;width: 15%;background-image: url("<?php print $iconsPath; ?>/info.png")'></div>
                    <div style='clear:both'></div>
                </div>
            </div>
        </div>


        <div id="myModal-info" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">

                    <div style='width:80%;height: 20px; float: left'><span>О программе</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='closeModal("-info")'
                                                                           class="close">X</span></div>

                    <div class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/s_info.png")'></div>
                    <div class='icon' style='height: 40px; line-height: 40px;'> Сведения</div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">
                    <div class='expand-animation' style='color:white'>
                        <hr>
                        Версия программы v1.2<br>
                        Разработчик &copy;house4u<br>
                        email: info@house4u.com.ua
                    </div>
                    <hr>
                </div>
                <div style='clear:both'></div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='closeModal("-info")'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myModal-reset" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">

                    <div style='width:80%;height: 20px; float: left'><span>Настройки</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='closeModal("-reset")' class="close">X</span>
                    </div>

                    <div class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/s_settings-ex.png")'></div>
                    <div class='icon' style='height: 40px; line-height: 40px;'>Сброс настроек</div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">
                    <div class='expand-animation'>
                        <center>
                            <button style='width:200px;margin-bottom: 5px;margin-top: 5px' class='modalButton'
                                    onclick='applyReset(1)'>Расписание комнат
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton' onclick='applyReset(2)'>
                                Название комнат
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton' onclick='applyReset(3)'>
                                Нет дома/Ручной
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton' onclick='applyReset(4)'>
                                Датчики/Реле
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton' onclick='applyReset(5)'>
                                Режим комнат
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton' onclick='applyReset(99)'>
                                СБРОСИТЬ ВСЕ
                            </button>
                            <button style='width:200px;margin-bottom: 5px' class='modalButton'
                                    onclick='applyReset(100)'>На контроллер
                            </button>
                        </center>
                    </div>
                </div>
                <div style='clear:both'></div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='closeModal("-reset")'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id="myModal4" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <div style='width: 50%; float: left'>
                        <div id='roomNameStr4'>--</div>
                        <div class='icon' style='height: 40px; line-height: 40px;'><span id='scheduleItemTodoId'>Температура</span>
                        </div>
                    </div>
                    <div style='width: 50%; float: left'>
                        <div class='icon ib3' onclick='changeShdeuleMode(0)'
                             style='width:20%;background-image: url("<?php print $iconsPath; ?>/t2.png")'></div>
                        <div class='icon ib3' onclick='changeShdeuleMode(2)'
                             style='width:50%;background-image: url("<?php print $iconsPath; ?>/mode-off.png")'></div>
                        <div class='icon ib3' onclick='changeShdeuleMode(1)'
                             style='width:30%;background-image: url("<?php print $iconsPath; ?>/mode-on.png")'></div>
                    </div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">
                    <div class='expand-animation' id='scheduleChangeTempId' style='height: 40px'>
                        <div class='icon ib3' style='width: 16%'></div>
                        <div class='icon ib3' onclick='addTemp(10,0,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTemp(0,1,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                        <div class='icon ib3' onclick='addTemp(0,0,0.5,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                        <div class='icon ib3'
                             style='width: 16%;background-image: url("<?php print $iconsPath; ?>/t2.png");'></div>
                        <div class='icon ib3 digit' id='_digit10'>2</div>
                        <div class='icon ib3 digit' id='_digit1'>2</div>
                        <div class='icon ib3 digit' id='_digit05'>.5</div>

                        <div class='icon ib3' style='width: 16%' onclick='selectMode(4)'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTemp(-10,0,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTemp(0,-1,0,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                        <div class='icon ib3' onclick='addTemp(0,0,-0.5,"_")'
                             style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    </div>

                    <div class='expand-animation' id='scheduleTurnOnId' style='height: 180px; display: none'>
                        <div class='icon'
                             style='width: 16%;background-image: url("<?php print $iconsPath; ?>/mode-on.png");'></div>
                        <div class='icon' style='width: 4%'></div>
                        <div class='icon' style='width: 80%;color:white;line-height: 180px'><b>Включить</b></div>
                    </div>

                    <div class='expand-animation' id='scheduleTurnOffId' style='height: 180px; display: none'>
                        <div class='icon'
                             style='width: 16%;background-image: url("<?php print $iconsPath; ?>/mode-off.png");'></div>
                        <div class='icon' style='width: 4%'></div>
                        <div class='icon' style='width: 80%;color:white;line-height: 180px'><b>Выключить</b></div>
                    </div>

                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyScheduleChangeTemp()'>OK</button>
                    </center>
                </div>
            </div>

        </div>

        <div id="myModal5" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">

                    <div style='width:80%;height: 20px; float: left'><span id='roomNameStr5'>--</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='closeModal(5)'
                                                                           class="close">X</span></div>

                    <div class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/s_mode-schedule.png")'></div>
                    <div class='icon' style='height: 40px; line-height: 40px;'><span id='currentTimeId1'>00:00</span>&nbsp;-&nbsp;<span
                                id='currentTimeId2'>23:59</span></div>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-body">
                    <div class='icon ib4' onclick='addTime(1,0,0,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib4' onclick='addTime(0,1,0,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib4' style='width: 2%'></div>
                    <div class='icon ib4' onclick='addTime(0,0,1,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>
                    <div class='icon ib4' onclick='addTime(0,0,0,1)'
                         style='background-image: url("<?php print $iconsPath; ?>/plus.png")'></div>

                    <div class='icon ib4 digit' id='digitH10'>2</div>
                    <div class='icon ib4 digit' id='digitH1'>2</div>
                    <div class='icon ib4 digit' style='width:2%;text-align:center'>:</div>
                    <div class='icon ib4 digit' id='digitM10'>5</div>
                    <div class='icon ib4 digit' id='digitM1'>9</div>


                    <div class='icon ib4' onclick='addTime(-1,0,0,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib4' onclick='addTime(0,-1,0,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib4' style='width: 2%'></div>
                    <div class='icon ib4' onclick='addTime(0,0,-1,0)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>
                    <div class='icon ib4' onclick='addTime(0,0,0,-1)'
                         style='background-image: url("<?php print $iconsPath; ?>/minus.png")'></div>


                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyScheduleTime()'>OK</button>
                    </center>
                </div>
            </div>

        </div>

        <div id='myModalMSG' class='modal'>
            <!-- Modal content -->
            <div class="modal-content2">
                <div class="modal-header">

                    <div id='msg-icon-id' class='icon'
                         style='height: 30px; width: 15%; background-image: url("<?php print $iconsPath; ?>/update.png")'></div>
                    <div class='icon' id='_msg-capid'
                         style='padding-left: 10px; height: 30px; width: 80%;line-height: 30px;'>Сохранение изменений...
                    </div>
                    <div style='clear:both'></div>
                </div>

                <div id='_msg-text-div' class="modal-body" style='display: none; width: 100%'>
                    <div id='_msg-text-id' style='color: white'></div>
                </div>
                <div id='_okButtonId' class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='closeModal("MSG")'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <div id='myModalMSG-confirm' class='modal'>
            <!-- Modal content -->
            <div class="modal-content2">
                <div class="modal-header">

                    <div id='msg-icon-id-confirm' class='icon'
                         style='height: 30px; width: 15%; background-image: url("<?php print $iconsPath; ?>/update.png")'></div>
                    <div class='icon' id='_msg-capid-confirm'
                         style='padding-left: 10px; height: 30px; width: 80%;line-height: 30px;'>Сохранение изменений...
                    </div>
                    <div style='clear:both'></div>
                </div>

                <div id='_msg-text-div-confirm' class="modal-body" style='display: none; width: 100%'>
                    <div id='_msg-text-id-confirm' style='color: white'></div>
                </div>
                <div id='_okButtonId-confirm' class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='okCancelConfirm(true)'>OK</button>
                        <button class='modalButton' onclick='okCancelConfirm(false)'>Отмена</button>
                    </center>
                </div>
            </div>
        </div>

        <div id='myModal7' class='modal'>
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <div style='width:80%;height: 20px; font-weight: bold; float: left'><span
                                id='msgcapid'>Сохранение</span></div>
                    <div style='width:20%;height: 20px; float: left'><span onclick='' class="close"></span></div>

                    <div id='msg-icon-id' class='icon'
                         style='height: 40px; width: 15%; background-image: url("<?php print $iconsPath; ?>/update.png")'></div>
                    <div class='icon' style='height: 40px; width: 85%;line-height: 40px;'><span id='msgcapid2'>Сохранение изменений...</span>
                    </div>
                    <div style='clear:both'></div>
                </div>

                <div id='msg-text-div' class="modal-body" style='display: none; width: 100%'>
                    <div id='msg-text-id' style='color: white'></div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>

        <div id="myModal-login" class="modal2">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <div style="height: 40px"><b>Вход в систему</b></div>

                </div>
                <div class="modal-body">
                    <div id='login-error-msg' style='color: white;'></div>
                    <br>
                    <div style='color: white'>Логин</div>
                    <input id='login' type='text' style='width:100%; height:40px; background: #D4D4D4'
                           onfocus='focusFunction()'' onblur='blurFunction()'></p>
                    <div style='color: white'>Пароль</div>
                    <input id='passw' type='password' style='width:100%; height:40px; background: #D4D4D4'
                           onfocus='focusFunction()'' onblur='blurFunction()'></p>
                    <br>
                    <div style='clear:both'></div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button class='modalButton' onclick='applyLogin()'>OK</button>
                    </center>
                </div>
            </div>
        </div>

        <script type='text/javascript'>
            var MainScreenIsCleanOfDialogs = true;
            var haveModeOrTemprChanges = false;
            var blinkWatchDot = false;
            var lastDataUpdateDate = new Date();
            var ang = new Array();
            var callStartRotation_START = true;
            var callStartRotation_STOP = true;
            var showHideDebugVar = false;
            var resetRoomSettingsVar = false;

            var modal = document.getElementById('myModal');
            var modal2 = document.getElementById('myModal2');
            var modal3 = document.getElementById('myModal3');
            var scheduleTempDialog = document.getElementById('myModal4');
            var scheduleTimeDialog = document.getElementById('myModal5');
            var advancedSettingsModal = document.getElementById('myModal6');

            var roomIdSelected = 0;

            var updateCounter = 0;
            var updateChangesCounter = 0;

            var stateX = <?php print $state; ?>;
            var requestPassVar = <?php print $requestPass; ?>;
            var useRmoteServerVar = <?php print htmlspecialchars(file_get_contents("settings/remote"));?>;
            var useRmoteServerVarTmp = useRmoteServerVar;
            var stateDebugStr = '<?php print $stateDebugStr; ?>';
            var roomsNum = <?php print $roomsNum; ?>;

            var thisServerUpdateTime = <?php print htmlspecialchars(file_get_contents("outputs/date.x"));?>;
            var remoteServerUpdateTime = 0;
            var weAreLocalServer = <?php print $weAreLocalServer; ?>;

            var lastChatModify = <?php print $lastChatModify; ?>;

            var modeXStr = '<?php print $modeXStrPrev; ?>';
            var modeXStrPrev = '<?php print $modeXStrPrev; ?>';

            var modeXWebUIDate2 = modeXStrPrev;

            var modeXControllerDate = <?php print $modeXControllerDate; ?>;
            var modeXWebUIDate = <?php print $modeXWebUIDate; ?>;

            function getUrlByMode(mode) {
                if (mode == 1)
                    return 'mode-hand.png';
                if (mode == 2)
                    return 'mode-schedule.png';
                if (mode == 3)
                    return 'mode-standby.png';
                if (mode == 4)
                    return 'mode-on.png';
                if (mode == 5)
                    return 'mode-off.png';
                if (mode == 7)
                    return 'individual.png';
            }

            function getModeName(mode) {
                if (mode == 1)
                    return 'ручной';
                if (mode == 2)
                    return 'по расписанию';
                if (mode == 3)
                    return 'никого нет дома';
                if (mode == 4)
                    return 'включить';
                if (mode == 5)
                    return 'выключить';
                if (mode == 7)
                    return 'индивидуально';
            }

            var additionalRoomSettings = false;
            var roomModeSelected = 0;

            function selectMode(mode) {

                MainScreenIsCleanOfDialogs = false;

                if (mode == 6) {
                    additionalRoomSettings = !additionalRoomSettings;
                    var disp = 'none';
                    if (additionalRoomSettings)
                        disp = 'block';

                    document.getElementById('additionalRoomSettingsId').style.display = disp;


                    if (additionalRoomSettings)
                        document.getElementById('advancedSettingsId').style.backgroundImage = 'url(<?php print $iconsPath;?>/settings-up.png)';
                    else
                        document.getElementById('advancedSettingsId').style.backgroundImage = 'url(<?php print $iconsPath;?>/settings.png)';

                    return;
                }

                if (mode == 8) {
                    roomAdancedSettingsClick();
                    return;
                }

                if (mode == 9) {
                    scheduleClick();
                    advancedSettingsScheduleClickUpdateUI();
                    return;
                }

                if (mode == 10) {
                    roomNumSettingsClick();
                    return;
                }

                for (var i = 1; i < 8; i++)
                    if (i == mode)
                        document.getElementById('selmodeid' + i).style.backgroundImage = 'url(<?php print $iconsPath;?>/select-bar.png)';
                    else
                        document.getElementById('selmodeid' + i).style.backgroundImage = 'none';

                roomModeSelected = mode;

                document.getElementById('modeTypeStr').innerHTML = getModeName(mode);
            }

            // ====================== RIGHT NOW MODE and ROOM MODE =======================  START
            function roomRightNowClcik(id) {
                MainScreenIsCleanOfDialogs = false;

                roomIdSelected = id;
                document.getElementById('currentTemp').innerHTML = '';//getModeName(currentMode[id]);
                document.getElementById('roomNameStr2').innerHTML = '<b>' + roomsName[id] + '</b>';

                modal3.style.display = "block";

                //alert('ha');

                timeOutModeTmp = timeOutMode;
                curTimeOutTmp = curTimeOut;
                setTimeOutFlagTmp = setTimeOutFlag;

                curTemp = getTempAccordingToMode(id);//rightNowTemp[id];

                //alert(id + ":"+curTemp);
                updateCurrentTemp(curTemp, "");

                addTimeOut(0, 0, 0, "");
                showHideTimeOutUI();
            }

            var DaysArr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
            var MonthArr = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Авгуси', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

            function getShortDate(date, chat) {
                var year = new Date();

                var y = date.getFullYear();
                var m = date.getMonth();
                var d = date.getDay();

                if (y == year.getFullYear())
                    y = "";

                var month = MonthArr[m] + ",";

                if (m == year.getMonth())
                    month = "";

                var day = date.getDate();
                Day = " " + day;
                DayName = DaysArr[d];

                DayStr = DayName + Day + ", ";

                if (day == year.getDate()) {
                    Day = "";
                    DayName = "";
                    DayStr = "";
                }


                var h = date.getHours();
                var M = date.getMinutes();
                var s = date.getSeconds();

                //if ( m < 10 ) m = '0' + m;
                //if ( d < 10 ) d = '0' + d;
                if (h < 10) h = '0' + h;
                if (M < 10) M = '0' + M;
                if (s < 10) s = '0' + s;


                s = ":" + s;
                if (chat) {
                    s = "";
                }

                return DayStr + month + " " + h + ":" + M + s + " " + y;
            }

            function updateTimeOutDate() {

                var koef = 0;

                if (timeOutModeTmp == 0)
                    koef = 60;
                else if (timeOutModeTmp == 1)
                    koef = 3600;
                else if (timeOutModeTmp == 2)
                    koef = 86400;

                var date = new Date(Date.now() + curTimeOutTmp * koef * 1000);

                document.getElementById('timeOutDateId').innerHTML = "<br><center>" + getShortDate(date, false) + "</center>";//Вт 23, Декабрь 21:30";
            }


            function showTimeOutDialog() {
                timeOutModeTmp = timeOutMode;
                curTimeOutTmp = curTimeOut;


                document.getElementById('selectedModeId-timeout').innerHTML = "Режим: " + getModeName(roomModeSelected);
                addTimeOut(0, 0, 0, "_");

                document.getElementById('myModal-timeout').style.display = 'block';
            }

            function applyTimeOutMode() {
                var timeOutModeTmp2 = timeOutMode;
                var curTimeOutTmp2 = curTimeOut;

                haveModeOrTemprChanges = true;  // aply timeout every time

                if (timeOutMode != timeOutModeTmp) {
                    //haveModeOrTemprChanges = true;
                    timeOutMode = timeOutModeTmp;
                }

                if (curTimeOut != curTimeOutTmp) {
                    //haveModeOrTemprChanges = true;
                    curTimeOut = curTimeOutTmp;
                }

                var doneOK = applyRoomMode(false);

                if (doneOK) {
                    document.getElementById('myModal-timeout').style.display = 'none';
                    if (askTimeOutTime)  // clear  askTimeOutTime => false;
                        selectTurnOffTime();
                } else {
                    timeOutMode = timeOutModeTmp2;
                    curTimeOut = curTimeOutTmp2;
                }
            }

            var askTimeOutTime = false;

            function selectTurnOffTime() {
                askTimeOutTime = !askTimeOutTime;

                if (askTimeOutTime)
                    document.getElementById('asid4').style.backgroundImage = 'url("<?php print $iconsPath;?>/clock-enabled.png")';
                else
                    document.getElementById('asid4').style.backgroundImage = 'url("<?php print $iconsPath;?>/clock.png")';
            }

            function applyRightNowMode() {
                var id = roomIdSelected;

                var rightNowTempTmp = rightNowTemp[id];
                var timeOutModeTmp2 = timeOutMode;
                var curTimeOutTmp2 = curTimeOut;

                if (rightNowTemp[id] != curTemp) {
                    rightNowTemp[id] = curTemp;
                    //alert(rightNowTemp[id]);
                    haveModeOrTemprChanges = true;
                }

                if (timeOutMode != timeOutModeTmp) {
                    timeOutMode = timeOutModeTmp;
                    haveModeOrTemprChanges = true;
                }

                if (curTimeOut != curTimeOutTmp) {
                    curTimeOut = curTimeOutTmp;
                    haveModeOrTemprChanges = true;
                }

                if (setTimeOutFlag != setTimeOutFlagTmp) {
                    setTimeOutFlag = setTimeOutFlagTmp;
                    haveModeOrTemprChanges = true;
                }

                if (setTimeOutFlag)  // if have 'timeoutflag' - apply anyway
                    haveModeOrTemprChanges = true;

                roomModeSelected = 1;

                //alert('a');
                var doneOK = applyRoomMode(false);  // save data on server inside...

                if (doneOK) {
                    closeModal('3');
                    //alert(timeOutMode);
                    document.getElementById('desiredTempId' + id).innerHTML = curTemp + "&deg;c";
                } else {
                    rightNowTemp[id] = rightNowTempTmp;
                    timeOutMode = timeOutModeTmp2;
                    curTimeOut = curTimeOutTmp2;
                }
                //alert('b');
            }

            function getRoomSettingsStr(id) {
                var timeout = "0;0";

                if (setTimeOutFlag || askTimeOutTime)
                    timeout = curTimeOut + ";" + timeOutMode;

                // roomNum; roomMode; rightNowTemp; timout; timeoutType; followAllHouse: relayOutput; temperatureSensor; standByTemp; scheduleNum; HHMMDD.D; ...HHMMDD.D

                var num = scheduleIntervalsNum[id];
                //alert(num);
                var P_or_room_num = roomsPOutputs[id];
                if (id == 0)
                    P_or_room_num = roomsNum;

                //alert(P_or_room_num);

                res = id + ';' + currentMode[id] + ';' + rightNowTemp[id] + ';' + timeout + ':' + P_or_room_num + ";" + roomsTsensors[id] + ";" + standByTemp[id] + ';' + num + ';';

                var schedule = '';
                var scheduleMode = '';

                for (var i = 0; i < num; i++) {
                    var time = scheduleArrTime[id][i];

                    //if ( time < 959 )
                    //  time = '0' + time;

                    schedule += time * 1000 + scheduleArrTemp[id][i] * 10 + ';';
                    scheduleMode += scheduleArrIntervalMode[id][i];
                }

                res += schedule + scheduleMode;
                //alert(res);
                //scheduleArrTime
                return res;
            }

            function encodeURL(str) {
                return str.replace(/\+/g, '-').replace(/\//g, '_').replace(/\=+$/, '');
            }

            function decodeUrl(str) {
                str = (str + '===').slice(0, str.length + (str.length % 4));
                return str.replace(/-/g, '+').replace(/_/g, '/');
            }

            function base64_encode(data) {	// Encodes data with MIME base64
                //
                // +   original by: Tyler Akins (http://rumkin.com)
                // +   improved by: Bayron Guevara

                var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
                var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = '';

                do { // pack three octets into four hexets
                    o1 = data.charCodeAt(i++);
                    o2 = data.charCodeAt(i++);
                    o3 = data.charCodeAt(i++);

                    bits = o1 << 16 | o2 << 8 | o3;

                    h1 = bits >> 18 & 0x3f;
                    h2 = bits >> 12 & 0x3f;
                    h3 = bits >> 6 & 0x3f;
                    h4 = bits & 0x3f;

                    // use hexets to index into b64, and append result to encoded string
                    enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
                } while (i < data.length);

                switch (data.length % 3) {
                    case 1:
                        enc = enc.slice(0, -2) + '==';
                        break;
                    case 2:
                        enc = enc.slice(0, -1) + '=';
                        break;
                }

                return enc;
            }


            function saveRooms(id, showSaveMsg) {
                if (showSaveMsg) {
                    document.getElementById('myModal7').style.display = 'block';
                    document.getElementById('msgcapid2').innerHTML = "Сохранение данных...";
                }

                var res = false;
                var vals = roomsNum + ";" + id + ";";
                //for ( var i = 0; i <= roomsNum; i++ )
                vals = vals + roomsName[id] + ";";

                var vals_b64 = encodeURL(base64_encode(vals));

                var hash = vals_b64.hashCode();
                if (hash < 0) hash = -hash;
                hash = hash.toString(16);

                //alert( roomsName[id] );

                vals = encodeURIComponent(vals);

                urlstate = scriptPath + 'r-apply.php?vals=' + vals + '&hash=' + hash + '&b64=' + vals_b64;

                //alert(urlstate);
                //return;

                $.ajax({url: urlstate, async: false, cache: false, timeout: 3000})
                    .done(function (data) {
                        //alert(data + "\n" + urlstate );

                        if (data == hash || data == 'ntc')
                            res = true;
                        else {
                            document.getElementById('myModal7').style.display = 'block'; // show error anyway
                            document.getElementById('msg-text-div').style.display = 'block';
                            document.getElementById('msg-text-id').innerHTML = data;
                        }

                        if (!res)
                            document.getElementById('msgcapid2').innerHTML = "Ошибка!";

                    })
                    .fail(function (data) {
                        //btn.value = apl+"-Err";
                    });

                //alert('x');
                if (showSaveMsg || !res)
                    setTimeout(function () {
                        if (!res)
                            document.getElementById('msgcapid2').innerHTML = "Ошибка!";

                        if (!res) {
                            setTimeout(function () {
                                document.getElementById('myModal7').style.display = 'none';
                                document.getElementById('msg-text-div').style.display = 'none';
                            }, 2200);
                        } else {
                            document.getElementById('myModal7').style.display = 'none';
                            document.getElementById('msg-text-div').style.display = 'none';
                        }
                    }, 800);

                return res;

            }

            function saveSettings(id, showSaveMsg, forceUpdate, forceUpdate2, skipDateX) {
                if (showSaveMsg) {
                    document.getElementById('myModal7').style.display = 'block';
                    document.getElementById('msgcapid2').innerHTML = "Сохранение данных...";
                }

                var res = false;
                var vals = getRoomSettingsStr(id) + ";";
                var hash = vals.hashCode();
                if (hash < 0) hash = -hash;
                hash = hash.toString(16) + ";";

                vals = vals + hash;

                urlstate = scriptPath + 's-apply.php?vals=' + vals;

                if (forceUpdate)
                    urlstate += "&force";

                if (forceUpdate2)
                    urlstate += "&force2";

                if (skipDateX)
                    urlstate += "&skipdatex";

                if (!skipDateX)
                    thisServerUpdateTime = Math.floor(Date.now() / 1000);

                urlstate += "&mxwebui=" + thisServerUpdateTime;

                //alert(thisServerUpdateTime + " " + modeXWebUIDate);
                //thisServerUpdateTime = modeXWebUIDate;

                //alert(urlstate);

                $.ajax({url: urlstate, async: false, cache: false, timeout: 3000})
                    .done(function (data) {
                        //alert(data + "\n" + urlstate );

                        if (data == hash || data == 'ntc')
                            res = true;
                        else {
                            document.getElementById('myModal7').style.display = 'block'; // show error anyway
                            document.getElementById('msg-text-div').style.display = 'block';
                            document.getElementById('msg-text-id').innerHTML = data;
                        }

                        if (!res)
                            document.getElementById('msgcapid2').innerHTML = "Ошибка!";

                    })
                    .fail(function (data) {
                        //btn.value = apl+"-Err";
                    });

                //alert('x');
                if (showSaveMsg || !res)
                    setTimeout(function () {
                        if (!res)
                            document.getElementById('msgcapid2').innerHTML = "Ошибка!";

                        if (!res) {
                            setTimeout(function () {
                                document.getElementById('myModal7').style.display = 'none';
                                document.getElementById('msg-text-div').style.display = 'none';
                            }, 2200);
                        } else {
                            document.getElementById('myModal7').style.display = 'none';
                            document.getElementById('msg-text-div').style.display = 'none';
                        }
                    }, 800);

                if (res)
                    updateSettingsFromRemoteHost();

                return res;
            }


            function applyRoomMode(skipRightNow) {
                var id = roomIdSelected;

                if (roomModeSelected == 1 && skipRightNow) {
                    // we have selected hnd mode, so call temperature UI
                    roomRightNowClcik(id);
                    return;
                }

                if (id == 0 && askTimeOutTime && skipRightNow) {
                    showTimeOutDialog();
                    return;
                }


                var haveModechanges = false;

                if (currentMode[id] != roomModeSelected) {
                    haveModeOrTemprChanges = true;
                    haveModechanges = true;
                }

                var doneOK = true;
                var curModeTmp = currentMode[id];

                //alert(haveModeOrTemprChanges);
                // we have mode changes or clear 'all house' flag from some room
                // or we have changed 'all house' settings

                // true if we have some room with individual settings
                var haveIndividualSettings = false;

                for (var i = 1; i < roomsNum + 1; i++)
                    if (followAllHouse[i] == 0 && i != id) {
                        haveIndividualSettings = true;
                        break;
                    }

                //alert( haveIndividualSettings + " " + id );

                // true if we are clear 'all house' flag from room with number = id (room number)
                var clearfollowAllHouseFlag = followAllHouse[id] == 1 || (id == 0);

                // but if we have no individual settings for 'all house' section or we are
                // selecting individual mode - no need to rewrite settings
                if (!haveIndividualSettings && id == 0 || currentMode[0] == 7)
                    clearfollowAllHouseFlag = false;

                //alert( haveIndividualSettings + " " + clearfollowAllHouseFlag );

                if (haveModeOrTemprChanges || clearfollowAllHouseFlag) {
                    currentMode[id] = roomModeSelected;
                    doneOK = saveSettings(id, true, clearfollowAllHouseFlag);

                    if (doneOK && clearfollowAllHouseFlag) {
                        var followAllHouseAreClear = true;

                        for (var i = 0; i < roomsNum + 1; i++)
                            if (followAllHouse[i] != 0 && i != id)   // excepting this room because it is not cleared for now...
                            {
                                followAllHouseAreClear = false;
                                break;
                            }

                        // if we have cleared all individual flags - back 'all home' to individual mode
                        if (followAllHouseAreClear && currentMode[0] != 7) {
                            var modeTmp = currentMode[0];
                            currentMode[0] = 7;
                            doneOK = saveSettings(0, true, false);
                            if (!doneOK)
                                currentMode[0] = modeTmp;
                        }
                    }
                }

                if (doneOK) {
                    closeModal('');

                    if (haveModeOrTemprChanges || clearfollowAllHouseFlag) {
                        //modeXWebUIDate = new Date().now();
                        //saveServerStr(0,modeXStrPrev+";"+shortDate(modeXControllerDate)+";"+shortDate(modeXWebUIDate));
                        // have to save this date on server and load when start app
                    }

                    //currentMode[id] = roomModeSelected;
                    // only here 1
                    updateSettings[id] = 1;
                    haveModeOrTemprChanges = false;
                    startListenXDirty();
                } else
                    currentMode[id] = curModeTmp;

                if (doneOK) {
                    /*if ( id == 0 )
    {
      for ( var i = 0; i < roomsName.length; i++ )
      {
        if ( currentMode[0] == 7 )
          followAllHouse[i] = 0;
        else
        {
          if ( i != 0 )
            followAllHouse[i] = 1;
        }
      }
    }
    else
      followAllHouse[id] = 0;  */

                    MainScreenIsCleanOfDialogs = true;
                }

                fillDesiredTemp();
                return doneOK;
            }

            // ====================== RightNow MODE ======================= STOP
            function getScheduleTemp(id, addDeg) {
                // get temperature according to schedule
                var currentdate = new Date();

                var scheduleArr = {};
                var i;

                for (i = 1; i < 6; i++)
                    scheduleArr[i] = scheduleArrTime[id][i - 1];

                scheduleArr[0] = 0;
                scheduleArr[6] = 2360;
                //console.log(scheduleArr);
                //console.log(scheduleIntervalsNum[id]);
                // conver time to num of seconds from 00:00

                var timeVal = currentdate.getHours() * 3600 + currentdate.getMinutes() * 60 + currentdate.getSeconds();

                for (i = 0; i < scheduleIntervalsNum[id]; i++) {
                    // conver time to num of seconds from 00:00
                    var timeValFromSchedule1 = Math.floor(scheduleArr[i] / 100) * 3600 + (scheduleArr[i] % 100) * 60;
                    var timeValFromSchedule2 = Math.floor(scheduleArr[i + 1] / 100) * 3600 + (scheduleArr[i + 1] % 100) * 60;

                    if (timeValFromSchedule1 >= timeVal && timeVal < timeValFromSchedule2)
                        break;
                }


                var mode = scheduleArrIntervalMode[id][i - 1];
                //console.log(scheduleArrIntervalMode[id]);
                //console.log(id+":"+(i-1)+":"+mode+" "+roomsName[id]);

                if (mode == 0) {
                    var res = scheduleArrTemp[id][i - 1];

                    if (addDeg)
                        res += "&deg;c";

                    return res;
                }

                if (mode == 1)
                    return 'вкл';
                else
                    return 'выкл';
            }

            function isNumeric(n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            }


            function callStartRotation(start) {
                var haveUpdates = false;

                for (var i = 0; i < roomsNum + 1; i++)
                    if (updateSettings[i] == 1) {
                        jQuery('#updateId' + i).rotate(ang[i]);
                        ang[i] += 4;

                        haveUpdates = true;
                    }

                //obj = document.getElementById('debugId');
                //obj.innerHTML = ang;

                if (haveUpdates)
                    setTimeout("callStartRotation()", 100);

            }

            //var cntr = 0;

            var objDebug = document.getElementById('debugId');
            var stop_startListenXDirty_Remote = false;

            function addDebugInfo(str) {
                objDebug.innerHTML += "<br>" + str;
            }

            function setDebugInfo(str) {
                objDebug.innerHTML = str;
            }

            var startListenXDirtyCount = 0;

            function startListenXDirty() {
                urlstate = scriptPath + 'get-xd.php';
                startListenXDirtyCount++;
                //console.log('startListenXDirty:'+startListenXDirtyCount);
                $.ajax({url: urlstate, cache: false, timeout: 3000})
                    .done(function (data) {
                        data2 = data.split(":");
                        if (data != "error" && data2[0] == "xdCD") {
                            //console.log('startListenXDirty:'+data2);
                            if (data2[1][1] == 1) // if we have x.dirty flag (controller has received data about changes), start rotation
                            {
                                if (callStartRotation_START) {
                                    for (var i = 0; i < roomsNum + 1; i++) {
                                        ang[i] = 0;
                                    }

                                    //console.log('startListenXDirty: START ROT');
                                    callStartRotation(true);
                                    callStartRotation_START = false;
                                }
                            }
                        }
                        //else
                        //  console.log('startListenXDirty: - TRASH:'+data);

                    })
                    .fail(function (data) {
                        //obj.innerHTML = "error";
                    });


                var haveUpdates = false;

                for (var i = 0; i < roomsNum + 1; i++)
                    if (updateSettings[i] == 1) {
                        haveUpdates = true;
                        break;
                    }

                var timeDelay = 1000;

                if (startListenXDirtyCount > 66) //  58 + 8*15 = 178 seconds
                    timeDelay = 30000;
                else if (startListenXDirtyCount > 36) // 25 + 11*3 = 58 seconds
                    timeDelay = 15000;
                else if (startListenXDirtyCount > 25) // 25 seconds
                    timeDelay = 3000;

                //console.log('startListenXDirty:'+timeDelay);

                if (haveUpdates)
                    setTimeout("startListenXDirty()", timeDelay);
                else {
                    callStartRotation_START = true;
                    callStartRotation_STOP = true;

                    startListenXDirtyCount = 0;

                    // stop 'startListenXDirty()' data on remote server
                    stop_startListenXDirty_Remote = true;
                    //updateTSensors();
                }
            }

            function applyReset(num) {
                var clearAll = false;

                if (num == 99)
                    clearAll = true;

                if (num == 1 || clearAll)  // schedules
                {
                    scheduleIntervalsNum = new Array(4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4);

                    for (var i = 0; i < 17; i++)
                        scheduleArrTime[i] = new Array(600, 900, 1700, 2100, 2200, 2300);

                    for (var i = 0; i < 17; i++)
                        scheduleArrTemp[i] = new Array(19, 23, 20, 22, 22, 22);

                    for (var i = 0; i < 17; i++)
                        scheduleArrIntervalMode[i] = new Array(0, 0, 0, 0, 0, 0);
                }

                if (num == 2 || clearAll) // rooms names
                {
                    roomsNum = 8;
                    roomsName[0] = "Мой Дом";
                    document.getElementById('rnId0').innerHTML = roomsName[0];

                    for (var i = 1; i < 17; i++) {
                        roomsName[i] = "Комната #" + i;
                        document.getElementById('rnId' + i).innerHTML = roomsName[i];
                    }
                }

                if (num == 3 || clearAll) // standBy&rightNow temperatures
                {
                    standByTemp = new Array(18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18);
                    rightNowTemp = new Array(22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22);
                }

                if (num == 4 || clearAll)  // sensors and relays
                {
                    roomsTsensors = new Array(1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
                    roomsPOutputs = new Array(8, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16);
                }

                if (num == 5 || clearAll) // rooms mode
                {
                    currentMode = new Array(7, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);
                }

                if (num == 100) // send cuurent state to controller
                {
                }

                var allAreOK = true;

                if (num == 2 || clearAll) // rooms names
                {
                    for (var i = 0; i < 17; i++)
                        if (!saveRooms(i, false))
                            allAreOK = false;
                }

                if (num != 2) {
                    for (var i = 0; i < 17; i++) {
                        if (!saveSettings(i, true, true, true))
                            allAreOK = false;
                        updateSettings[i] = 1;
                    }
                }

                //modeXWebUIDate = new Date().now();
                //saveServerStr(0,modeXStrPrev+";"+shortDate(modeXControllerDate)+";"+shortDate(modeXWebUIDate));

                if (allAreOK)
                    myAlert("Данные сброшены успешно", "Сброс настроек...", '<?php print $iconsPath;?>/s_settings-ex.png');
                else
                    myAlert("Ошибка - повторите попытку", "Сброс настроек...", '<?php print $iconsPath;?>/s_settings-ex.png');

            }

            function resetRooms() {
                document.getElementById('myModal-reset').style.display = 'block';
            }

            function showInfo() {
                document.getElementById('myModal-info').style.display = 'block';
            }


            function resetRoomSettings() {
                resetRoomSettingsVar = !resetRoomSettingsVar;

                var url = 'url("<?php print $iconsPath;?>/check-false.png")';
                if (resetRoomSettingsVar) {
                    //objDebug.style.display = "block";
                    url = 'url("<?php print $iconsPath;?>/check-true.png")';
                }
                //else
                //  objDebug.style.display = "none";

                document.getElementById('resetRoomSettingsId').style.backgroundImage = url;
            }

            function showHideDebug() {
                showHideDebugVar = !showHideDebugVar;

                var url = 'url("<?php print $iconsPath;?>/check-false.png")';
                if (showHideDebugVar) {
                    objDebug.style.display = "block";
                    url = 'url("<?php print $iconsPath;?>/check-true.png")';
                } else
                    objDebug.style.display = "none";

                document.getElementById('showHideDebugId').style.backgroundImage = url;
            }


            function changePassword(text) {

                urlstate = scriptPath + 's-pass.php?set=' + text;

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        //alert(data);
                        var vals = text;
                        var hash = vals.hashCode();
                        if (hash < 0) hash = -hash;
                        hash = hash.toString(16);

                        if (data == hash) {
                            myAlert("Успешно!", "Смена пароля", '<?php print $iconsPath;?>/empty20.png');

                            document.getElementById('room-passw-text').value = "";
                            applyRoomNum();
                        } else {
                            myAlert("Ошибка - повторите позже.", "Смена пароля", '<?php print $iconsPath;?>/s-fail.png');
                        }
                    })
                    .fail(function (data) {
                        myAlert("Ошибка - повторите позже.", "Смена пароля", '<?php print $iconsPath;?>/s-fail.png');
                    });

            }


            function updateUseRmoteServerUI() {
                var url = 'url("<?php print $iconsPath;?>/check-false.png")';
                if (useRmoteServerVar)
                    url = 'url("<?php print $iconsPath;?>/check-true.png")';

                document.getElementById('useRmoteServerId').style.backgroundImage = url;
            }

            function applyUseRmoteServerVal() {
                useRmoteServerVar = useRmoteServerVarTmp;
                updateUseRmoteServerUI();
            }

            function useRmoteServer() {
                useRmoteServerVarTmp = !useRmoteServerVar;
                saveServerStr(2, useRmoteServerVarTmp + "", applyUseRmoteServerVal);
            }

            function updateRequestPassUi() {
                var url = 'url("<?php print $iconsPath;?>/check-false.png")';
                if (requestPassVar)
                    url = 'url("<?php print $iconsPath;?>/check-true.png")';

                document.getElementById('requestPassId').style.backgroundImage = url;
            }

            function requestPass() {
                requestPassVar = !requestPassVar;

                urlstate = scriptPath + 's-auth.php?set=' + requestPassVar;

                //alert(urlstate);

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        if (data != "error") {
                            updateRequestPassUi();
                            myAlert("Успешно!", "Настройки...", '<?php print $iconsPath;?>/empty20.png');
                        } else {
                            requestPassVar = !requestPassVar;
                            myAlert("Ошибка - повторите позже.", "Настройки...", '<?php print $iconsPath;?>/s-fail.png');
                        }
                    })
                    .fail(function (data) {
                        requestPassVar = !requestPassVar;
                        myAlert("Ошибка - повторите позже.", "Настройки...", '<?php print $iconsPath;?>/s-fail.png');
                    });
            }

            function shortDate(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();

                var h = date.getHours();
                var M = date.getMinutes();
                var s = date.getSeconds();

                if (m < 10) m = '0' + m;
                if (d < 10) d = '0' + d;
                if (h < 10) h = '0' + h;
                if (M < 10) M = '0' + M;
                if (s < 10) s = '0' + s;

                var offset = -(date.getTimezoneOffset() / 60);

                sign = "_";
                if (offset < 0)
                    sign = "-";

                if (offset < 10)
                    offset = "0" + offset;


                var ymd = y + '-' + m + '-' + d + ' ' + h + ':' + M + ':' + s;//+sign+offset+':00';

                //return new Date(ymd);
                return ymd;
            }

            function digitalWatch() {
                var date = new Date();
                var hours = date.getHours();
                var minutes = date.getMinutes();
                //var seconds = date.getSeconds();
                if (hours < 10) hours = "0" + hours;
                if (minutes < 10) minutes = "0" + minutes;
                //if (seconds < 10) seconds = "0" + seconds;
                var dot = "gray";
                if (blinkWatchDot)
                    dot = "white";
                blinkWatchDot = !blinkWatchDot;

                var interval = Math.floor(new Date().getTime() / 1000 - lastDataUpdateDate);

                var haveUpdates = false;

                for (var i = 0; i < roomsNum + 1; i++)
                    if (updateSettings[i] == 1) {
                        haveUpdates = true;
                        break;
                    }

                if (haveUpdates && startListenXDirtyCount == 0) {
                    //console.log('ha');
                    startListenXDirty();
                }
                //showHideDebugVar = true;
                if (showHideDebugVar) {
                    objDebug.style.display = "block";

                    //modeXStr = modeXStr + "";

                    //console.log('modeXStr:'+modeXStr+":"+modeXStr.length);
                    //console.log('currentMode:'+currentMode);

                    var curModeStr = "";
                    for (var i = 0; i < modeXStr.length; i++)
                        curModeStr += currentMode[i];

                    setDebugInfo("Информация");
                    addDebugInfo("контр: " + modeXStr);
                    addDebugInfo("сервр: " + curModeStr);
                    addDebugInfo("Изменение выходов: " + stateDebugStr);
                    addDebugInfo("Обновл mode контр: " + shortDate(new Date(modeXControllerDate * 1000)));
                    addDebugInfo("Обновл mode webUI: " + shortDate(new Date(thisServerUpdateTime * 1000)));
                    addDebugInfo("Обновл mode2сервр: " + modeXWebUIDate2);

                    addDebugInfo("Последние _данные: " + shortDate(new Date(lastDataUpdateDate * 1000)));

                    addDebugInfo(interval + " сек назад");
                    addDebugInfo("thisServerUpdateTime: " + shortDate(new Date(thisServerUpdateTime * 1000)));
                    if (remoteServerUpdateTime == 0)
                        addDebugInfo("remoteServerUpdateTime: --");
                    else
                        addDebugInfo("remoteServerUpdateTime: " + shortDate(new Date(remoteServerUpdateTime * 1000)));
                    //addDebugInfo("remoteServerUpdateTime: "+remoteServerUpdateTime);


                    //objDebug.setDebugData()
                    //objDebug.innerHTML = modeXStr + "<br>" + curModeStr + "<br>s:" + modeXControllerDate + "<br>w:" + modeXWebUIDate + "<br>"+interval;
                }

                if (interval < 60) {
                    document.getElementById('allHouseId0').style.backgroundImage = "";//"url('<?php print $iconsPath;?>/s-ok.png')";

                    // server             // web UI
                    // modeXControllerDate      modeXWebUIDate

                    if (modeXControllerDate > thisServerUpdateTime)  // update from Controller -> Web UI
                    {
                        for (var i = 0; i < modeXStr.length; i++) {
                            //if ( currentMode[i] == 1 ) // only back from rightNow mode for now
                            if (currentMode[i] != modeXStr[i] && updateSettings[i] == 0) {
                                var curModeTmp = currentMode[i];
                                currentMode[i] = modeXStr[i];
                                doneOK = saveSettings(i, true, false, false, true);

                                if (!doneOK)
                                    currentMode[i] = curModeTmp;
                            }
                        }
                    }
                } else {
                    var url = "url('<?php print $iconsPath;?>/s-fail.png')";
                    if (blinkWatchDot)
                        url = "";
                    document.getElementById('allHouseId0').style.backgroundImage = url;
                }

                url = "url('<?php print $iconsPath;?>/s-chat.png')";

                if (haveNewMessage && blinkWatchDot)
                    url = "";


                document.getElementById('chatIconId').style.backgroundImage = url;

                document.getElementById("Tx_0").innerHTML = hours + '<font color="' + dot + '">:</font>' + minutes;//+"<font color='gray'>("+status+")</font>";// + ":" + seconds;
                setTimeout("digitalWatch()", 1000);
            }

            String.prototype.replaceAt = function (index, character) {
                return this.substr(0, index) + character + this.substr(index + character.length);
            }

            function saveServerStr(code, str, callbackFunction) {
                // 0 - modeXStrPrev, modeXControllerDate, modeXWebUIDate  settings/mode.x
                var hash = str.hashCode();
                if (hash < 0) hash = -hash;
                hash = hash.toString(16);

                urlstate = scriptPath + 's-str.php?code=' + code + '&str=' + str + '&hash=' + hash;

                //alert(urlstate);

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        //alert(data);

                        if (data == hash) {
                            //alert('OK');
                            callbackFunction();
                            //alert('OK - saveServerStr');
                        }
                    })
                    .fail(function (data) {
                        //alert('Fail');
                    });
            }

            function clearReloadX() {
                var str = "clear_reload";

                var hash = str.hashCode();
                if (hash < 0) hash = -hash;
                hash = hash.toString(16);

                urlstate = scriptPath + 's-str.php?code=1' + '&str=' + str + '&hash=' + hash;

                //alert(urlstate);

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        if (data == "OK-1")
                            reloadUI();
                    })
                    .fail(function (data) {
                    });
            }

            function closeModalAll() {
                closeModal('');
                closeModal('2');
                closeModal('3');
                closeModal('4');
                closeModal('5');
                closeModal('6');
                closeModal('7');
                closeModal('8');

                closeModal('-timeout');
                closeModal('-reset');
                closeModal('MSG');

            }

            function reloadUI() {
                if (!MainScreenIsCleanOfDialogs)
                    alert("Данные на сервере изменились.\nИнтерфейс будет перезагружен!");

                closeModalAll();
                reloadUI2();
                //location.reload(true);
            }

            function reloadUI2() {
                // reload all settings from server without updating the page
                urlstate = scriptPath + 'ui-reload.php?code=1';

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        var sep = "#$^";
                        var sep2 = "*&^";

                        //alert(data);
                        var allParams = data.split(sep);
                        var vals = "";

                        var len = allParams.length;

                        for (var i = 0; i < len - 1; i++) {
                            vals += allParams[i];
                            if (i < allParams.length - 2)
                                vals += sep;
                        }

                        if (md5(vals) != allParams[len - 1])
                            return;

                        for (var i = 0; i < allParams.length; i++) {
                            if (allParams[i].indexOf(sep2) >= 0) {
                                var arr2 = allParams[i].split(sep2);
                                allParams[i] = arr2;

                                for (var j = 0; j < arr2.length; j++) {
                                    var arr = (allParams[i][j] + "").split(",");
                                    allParams[i][j] = arr;
                                }
                            }

                            if (allParams[i].indexOf(',') > 0) {
                                var arr = (allParams[i] + "").split(",");
                                allParams[i] = arr;
                            }

                            //console.log("I="+i+" "+allParams[i] + " len:"+allParams[i].length);
                            //str +=  "["+allParams[i] + "]\n";

                            /*
        printArray('currentMode', $currentMode);
        printArray('rightNowTemp', $rightNowTemp);
        printArray('standByTemp', $standByTemp);
        printArray('scheduleIntervalsNum', $scheduleIntervalsNum);
        printArray('roomsName', $roomsName);
        printArray('roomsTsensors', $roomsTsensors);
        printArray('roomsPOutputs', $roomsPOutputs);
        printArray('updateSettings', $updateSettings);
        printArray('followAllHouse', $followAllHouse);
        printArray('followAllHouse2', $followAllHouse);

        printArray2('scheduleArrTime',$scheduleArrTime);
        printArray2('scheduleArrTemp',$scheduleArrTemp);
        printArray2('scheduleArrIntervalMode',$scheduleArrIntervalMode);
        */
                        }

                        //console.log(currentMode+"\n"+allParams[0]);

                        coppyArray_int(currentMode, allParams[0]);
                        coppyArray_int(rightNowTemp, allParams[1]);
                        coppyArray_int(standByTemp, allParams[2]);
                        coppyArray_int(scheduleIntervalsNum, allParams[3]);
                        coppyArray(roomsName, allParams[4]);

                        coppyArray_int(roomsTsensors, allParams[5]);

                        coppyArray_int(roomsPOutputs, allParams[6]);
                        coppyArray_int(updateSettings, allParams[7]);
                        coppyArray_int(followAllHouse, allParams[8]);
                        coppyArray_int(followAllHouse2, allParams[9]);

                        coppyArray2(scheduleArrTime, allParams[10]);
                        coppyArray2(scheduleArrTemp, allParams[11]);
                        coppyArray2(scheduleArrIntervalMode, allParams[12]);

                        //alert(stateX + "\n" + allParams[13][0]*1);
                        stateX = allParams[13][0] * 1;
                        //alert(roomsNum + "\n" + allParams[13][1]*1);
                        roomsNum = allParams[13][1] * 1;
                        //alert(lastChatModify + "\n" + allParams[13][2]*1);
                        lastChatModify = allParams[13][2] * 1;
                        //alert(modeXStr + "\n" + allParams[13][3]);
                        modeXStr = allParams[13][3] + "";

                        modeXStrPrev = modeXStr;
                        modeXWebUIDate2 = modeXStrPrev;

                        //alert(modeXControllerDate + "\n" + allParams[13][4]*1);
                        modeXControllerDate = allParams[13][4] * 1;
                        //alert(modeXWebUIDate + "\n" + allParams[13][5]*1);
                        modeXWebUIDate = allParams[13][5] * 1;
                        //alert(weAreLocalServer + "\n" + allParams[13][6]);
                        weAreLocalServer = false;
                        if (allParams[13][6] == "true")
                            weAreLocalServer = true;

                        //alert(requestPassVar + "\n" + allParams[13][7]);
                        requestPassVar = false;
                        if (allParams[13][7] == "true")
                            requestPassVar = true;

                        //alert(stateDebugStr + "\n" + allParams[13][8]);
                        stateDebugStr = allParams[13][8];

                        //alert(useRmoteServerVar + "\n" + allParams[13][9]);
                        useRmoteServerVar = false;
                        if (allParams[13][9] == "true")
                            useRmoteServerVar = true;

                        useRmoteServerVarTmp = useRmoteServerVar;

                        //alert(thisServerUpdateTime + "\n" + allParams[13][10]*1);
                        thisServerUpdateTime = allParams[13][10] * 1;

                        fillDesiredTemp();
                        updateNumberOfRooms();
                        updateRoomNames();

                    })
                    .fail(function (data) {
                    });

            }

            function sendDataToRemoteServer() {
                // DATA -> REMOTE

                urlstate = scriptPath + 'get-remote.php?sendnew&update=' + thisServerUpdateTime

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        //console.log(data);
                        //alert(data);
                        //if ( data == "HASH-OK" )
                        //  thisServerUpdateTime = remoteServerUpdateTime;

                    })
                    .fail(function (data) {
                    });
            }

            function updateDataFromRemoteServer(updateTime) {
                // DATA <- REMOTE

                urlstate = scriptPath + 'get-remote.php?getnew&update=' + thisServerUpdateTime

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        //console.log(data);
                        thisServerUpdateTime = updateTime;
                        reloadUI();

                    })
                    .fail(function (data) {
                    });
            }

            function updateSettingsFromRemoteHost() {
                if (!weAreLocalServer || !useRmoteServerVar)
                    return;

                urlstate = scriptPath + 'get-remote.php?update=' + thisServerUpdateTime;

                //if ( stop_startListenXDirty_Remote )
                //  urlstate += "&clear";

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        if (data == "")
                            return;

                        if (data == "EXPIRED") {
                            myAlert("ilog2.com - подписка истекла!<br>Дист.управление отключено.", "Управление...", '<?php print $iconsPath;?>/s-fail.png');
                            useRmoteServer(); // turn off useRmoteServer
                            return;
                        }

                        var data2 = data.split(":");
                        remoteServerUpdateTime = data2[0] * 1;

                        /*
       lastChatModify2 = data2[1]*1;
       if ( !showChatVar && lastChatModify2 > lastChatModify )
            haveNewMessage = true; */


                        if (stop_startListenXDirty_Remote && data2[2] == "OK")
                            stop_startListenXDirty_Remote = false;

                        //alert(thisServerUpdateTime - remoteServerUpdateTime + " " + thisServerUpdateTime + "?" + remoteServerUpdateTime);
                        // have to update data here from remote server
                        if (thisServerUpdateTime < remoteServerUpdateTime) {
                            //alert("GET DATA <- REMOTE: "+ thisServerUpdateTime+"?"+remoteServerUpdateTime+":"+(thisServerUpdateTime-remoteServerUpdateTime));
                            //console.log("GET DATA <- REMOTE: "+ thisServerUpdateTime+"?"+remoteServerUpdateTime+":"+(thisServerUpdateTime-remoteServerUpdateTime));
                            updateDataFromRemoteServer(remoteServerUpdateTime);

                        } else
                            // have to send new data to server
                        if (thisServerUpdateTime > remoteServerUpdateTime) {
                            //alert("SEND DATA -> REMOTE: "+ thisServerUpdateTime+"?"+remoteServerUpdateTime+":"+(thisServerUpdateTime-remoteServerUpdateTime));
                            //console.log("SEND DATA -> REMOTE: "+ thisServerUpdateTime+"?"+remoteServerUpdateTime+":"+(thisServerUpdateTime-remoteServerUpdateTime));
                            sendDataToRemoteServer(remoteServerUpdateTime);
                        }
                        // else
                        // if ( thisServerUpdateTime ==  remoteServerUpdateTime )
                        // - nothing to do!!!
                    })
                    .fail(function (data) {
                    });
            }

            var haveNewMessage = false;

            function updateTSensors() {
                urlstate = scriptPath + 'get-ld.php';

                $.ajax({url: urlstate, cache: false, timeout: 2000})
                    .done(function (data) {
                        //alert(data);
                        var partsTmp = data.split("#");
                        //console.log('partsTmp = ' + partsTmp);
                        if (data != "error" && partsTmp[0] == "pL1Se") {
                            //console.log(data);
                            //var parts = partsTmp[2].split(";"); // mode.x file

                            //console.log(partsTmp);
                            thisServerUpdateTimeNew = partsTmp[2];
                            var reload_X = partsTmp[4]; //

                            //alert(partsTmp);
                            console.log('partsTmp[2]', partsTmp[2], 'reload_X = partsTmp[4]', partsTmp[4])
                            if (reload_X * 1 == 1) {
                                //alert("Требуется перезагрузка 1");
                                //console.log('clearReloadX call...');
                                clearReloadX();
                                return;
                            }

                            //console.log(thisServerUpdateTimeNew + " " + thisServerUpdateTime);
                            if (thisServerUpdateTimeNew > thisServerUpdateTime) {
                                //alert("Требуется перезагрузка 2");
                                // we have changes from other user
                                //thisServerUpdateTime = thisServerUpdateTimeNew;
                                //console.log('reloadUI call...');
                                // have to reload all
                                reloadUI();
                                thisServerUpdateTime = thisServerUpdateTimeNew;
                                return;
                            }

                            if (!showChatVar && partsTmp[3] * 1 > lastChatModify) {

                                //lastChatModify = partsTmp[3]*1;
                                haveNewMessage = true;

                                //alert(lastChatModify);
                            }

                            parts = partsTmp[1].split(";");

                            stateX = parts[2];

                            console.log('parts', parts);
                            console.log('roomsTsensors', roomsTsensors);
                            for (var i = 1; i < roomsNum + 1; i++) {
                                obj = document.getElementById('Tx_' + i);
                                //alert('Tx_'+roomsTsensors[i]);
                                val = parts[3 + roomsTsensors[i]];
                                //console.log(parts);
                                //console.log('Tx_'+roomsTsensors[i]+" val:"+val);
                                //alert('Tx_'+roomsTsensors[i]+':'+val);
                                if (val == "")
                                    val = "--";
                                else
                                    val = parseFloat(val).toFixed(1);
                                obj.innerHTML = val + "&deg;c";
                            }

                            //alert(followAllHouse);
                            var allHouseFlag = parseInt(parts[24], 16).toString(2);
                            numOfZero = 16 - allHouseFlag.length;

                            for (var i = 0; i < numOfZero; i++)
                                allHouseFlag = '0' + allHouseFlag;

                            //console.log('allhouse: '+allHouseFlag);
                            //alert(parts[24]+":"+allHouseFlag);
                            for (var i = 0; i < 16; i++) {
                                if (i < allHouseFlag.length)
                                    followAllHouse[16 - i] = allHouseFlag[i];
                                else
                                    followAllHouse[16 - i] = 0;
                            }
                            //console.log('allHOUSE: '+followAllHouse);
                            //alert(followAllHouse);


                            // mode in which roomsa are really are (from 'latestdata')
                            modeXStr = parts[23] + "";

                            // time when we have received fresh data from controller  from 'latestdata')
                            lastDataUpdateDate = parts[0];

                            if (parts[1] == "BAD CRC") {
                                myAlert("Нет активации! Контроллер не активирован для работы с внутренним web-интерфейсом.", "Активация", '<?php print $iconsPath;?>/s_settings-ex.png');
                                ;
                                return;
                            }

                            //console.log(parts);
                            //console.log("modeXStr.length="+modeXStr.length);

                            var curModeStr = "";
                            for (var i = 0; i < modeXStr.length; i++)
                                curModeStr += currentMode[i];

                            if (modeXStr != modeXStrPrev) {
                                modeXStrPrev = modeXStr;

                                if (modeXStr != curModeStr) {
                                    //console.log('Get new modeXStr:\n'+modeXStr+"\n"+curModeStr);

                                    modeXControllerDate = lastDataUpdateDate;

                                    //console.log( modeXControllerDate - thisServerUpdateTime );
                                }
                            }


                            /*if ( modeXStr != modeXStrPrev )
         {
           // remeber the time of last xMode changes
           alert(modeXStr+" "+modeXStrPrev);
           modeXControllerDate = lastDataUpdateDate;
           modeXStrPrev = modeXStr;

           //alert('Новые данные от кнтроллера сохраняем');
           saveServerStr(0,modeXStrPrev+";"+modeXControllerDate+";"+modeXWebUIDate);
         }   */

                            //alert(lastDataUpdateDate);
                            //lastDataUpdateDate = new Date();
                        }
                    })
                    .fail(function (data) {
                    });
            }


            function clearUpdateStatus() {
                //alert('here');

                urlstate = scriptPath + 'get-st.php';

                updateCounter++;
                updateChangesCounter++;

                if (updateCounter >= 3) {
                    updateTSensors();
                    updateCounter = 0;
                }

                if (updateChangesCounter >= 5) {
                    updateSettingsFromRemoteHost();
                    updateChangesCounter = 0;
                }

                $.ajax({url: urlstate, cache: false, async: true, timeout: 2000})
                    .done(function (data) {
                        data2 = data.split(":");

                        if (data != "error" && data2[0] == "stAB") {
                            data = data2[1];
                            //alert(data);
                            stateDebugStr = data;

                            var wasUpdates = false;

                            for (var i = 0; i < 17; i++)
                                if (data[i] == 0) // only here 4
                                {
                                    if (updateSettings[i] == 1)
                                        wasUpdates = true;

                                    updateSettings[i] = 0;
                                }

                            if (wasUpdates) {
                                updateTSensors();
                                updateCounter = 0;
                            }
                        }
                    })
                    .fail(function (data) {
                    });
            }

            function dec2bin(dec) {
                return (dec >>> 0).toString(2);
            }

            var wasUpdateChanges = false;

            function fillDesiredTemp() {
                // call it every 2 seconds
                if (debug) {
                    obj = document.getElementById('debugId');
                    obj.style.display = "block";

                    urlstate = scriptPath + 'get-debug-data.php';

                    $.ajax({url: urlstate, cache: false, timeout: 3000})
                        .done(function (data) {
                            //alert(data + "\n" + urlstate );
                            obj.innerHTML = data;

                        })
                        .fail(function (data) {
                            obj.innerHTML = "error";
                        });
                }

                //console.log(roomsName);
                //console.log(roomsName.length);

                for (var i = 0; i < roomsName.length; i++) {
                    var mode = currentMode[i];
                    var Temp = '--';

                    if (mode == 1)
                        Temp = rightNowTemp[i];
                    else if (mode == 3)
                        Temp = standByTemp[i];
                    else if (mode == 4)
                        Temp = 'вкл';
                    else if (mode == 5)
                        Temp = 'выкл';

                    if (mode == 2)
                        Temp = getScheduleTemp(i, true);

                    if (isNumeric(Temp))
                        Temp += "&deg;c";

                    //console.log('desiredTempId'+i);
                    document.getElementById('desiredTempId' + i).innerHTML = Temp;
                    //console.log('OK');

                    if (updateSettings[i] == 1)
                        document.getElementById('updateId' + i).style.backgroundImage = "url('<?php print $iconsPath;?>/update.png')";
                    else {
                        document.getElementById('updateId' + i).style.backgroundImage = "";
                        jQuery('#updateId' + i).rotate(0);
                    }

                    //=========
                    document.getElementById('roomModeId' + i).style.backgroundImage = "url('<?php print $iconsPath;?>/" + getUrlByMode(currentMode[i]) + "')";

                    if (followAllHouse[i] == 0 || currentMode[0] == 7) {
                        if (i != 0) {
                            document.getElementById('myBtn' + i).style.background = "#5C5C5C";

                            if (followAllHouse[i] == 0)
                                document.getElementById('allHouseId' + i).style.backgroundImage = "url('<?php print $iconsPath;?>/empty20.png')";
                            else
                                document.getElementById('allHouseId' + i).style.backgroundImage = "url('<?php print $iconsPath;?>/s_" + getUrlByMode(modeXStr[0]) + "')";
                        }
                    } else {
                        if (i != 0) {
                            document.getElementById('allHouseId' + i).style.backgroundImage = "url('<?php print $iconsPath;?>/s_" + getUrlByMode(currentMode[0]) + "')";
                            document.getElementById('myBtn' + i).style.background = "#8C8C8C";
                        }
                    }
                    //=========

                    var bitFlag = followAllHouse[i];
                    //console.log('followAllHouse',followAllHouse, 'followAllHouse2', followAllHouse2 );
                    if (bitFlag != followAllHouse2[i]) {
                        followAllHouse2[i] = bitFlag;
                        wasUpdateChanges = true;

                    }
                }

                // update pump states...
                var state = stateX; //dec2bin(stateX);

                //alert(stateX + " " + state);
                //console.log('state = stateX: ' + state);
                //console.log(roomsPOutputs);
                for (var i = 0; i < 16; i++) {

                    //console.log("Реле комнаты " + (i+1) + " P"+roomsPOutputs[i+1]);
                    relay_num = roomsPOutputs[i + 1];

                    var imgUrl = "";
                    if (state % 2 == 1) {
                        imgUrl = "<?php print $iconsPath;?>/h.png";
                        let state_2 = state % 2;
                        //console.log('relay_num' + (i +1) + ' state =' + state + ' state % 2 = ' + state_2);
                    }

                    relay_num = 1;
                    for (var j = 1; j < 17; j++) {
                        if (roomsPOutputs[j] == i + 1) {
                            relay_num = j;
                            break;
                        }
                    }

                    document.getElementById('hs' + relay_num).style.backgroundImage = "url('" + imgUrl + "')";
                    //console.log('relay_num' + (i +1) + 'state before =' + state);
                    state = Math.floor(state / 2);
                    //console.log('relay_num' + (i +1) + 'state after =' + state);
                }

                var imgUrl = "";
                if (stateX != 0)
                    imgUrl = "<?php print $iconsPath;?>/gaz.png";

                document.getElementById('hs0').style.backgroundImage = "url('" + imgUrl + "')";


                if (wasUpdateChanges) {
                    var allHouseFlag = "";

                    for (var i = 0; i < 17; i++)
                        allHouseFlag = allHouseFlag + followAllHouse[i] + "";

                    var vals = allHouseFlag;

                    var hash = vals.hashCode();
                    if (hash < 0) hash = -hash;
                    hash = hash.toString(16);

                    urlstate = scriptPath + 'f-apply.php?vals=' + vals + "&hash=" + hash;
                    //alert(urlstate);

                    $.ajax({url: urlstate, cache: false, timeout: 3000})
                        .done(function (data) {
                            if (data == hash)
                                wasUpdateChanges = false;
                        })
                        .fail(function (data) {
                            //obj.innerHTML = "error";
                        });
                }
            }

            function getTempAccordingToMode(id) {
                var i = id;

                var mode = currentMode[i];

                var Temp = '--';

                if (mode == 1)
                    Temp = rightNowTemp[i];
                else if (mode == 3)
                    Temp = standByTemp[i];
                else if (mode == 4)
                    Temp = 22;
                else if (mode == 5)
                    Temp = 22;

                var tempVal = Temp;

                if (mode == 2)
                    tempVal = getScheduleTemp(i, false);

                if (!isNumeric(tempVal))
                    return 22;

                return tempVal;
            }

            function closeModal(id) {
                if (id == "")
                    MainScreenIsCleanOfDialogs = true;

                var modal = document.getElementById('myModal' + id);
                modal.style.display = "none";
            }

            function updateRoomNameStr(roomName) {
                document.getElementById('roomNameStr').innerHTML = roomName;
                document.getElementById('roomNameStr3').innerHTML = roomName;
                document.getElementById('roomNameStr4').innerHTML = roomName;
                document.getElementById('roomNameStr5').innerHTML = roomName;
                document.getElementById('roomNameStr6').innerHTML = roomName;
                document.getElementById('roomNameStr7').innerHTML = roomName;
            }

            function roomClick(id) {
                // Get the modal
                roomIdSelected = id;
                roomModeSelected = currentMode[id];

                document.getElementById('modeTypeStr').innerHTML = getModeName(currentMode[id]);
                var roomName = '<b>' + roomsName[id] + '</b>';

                updateRoomNameStr(roomName);

                selectMode(roomModeSelected);

                if (id == 0) // all house, have to add "individual" icon   and hide 'standby' temperature
                {
                    document.getElementById('ib1').className = 'icon ib2';
                    document.getElementById('ib1').style.backgroundImage = "url('<?php print $iconsPath;?>/" + getUrlByMode(1) + "')";
                    document.getElementById('ib2').className = 'icon ib2';
                    document.getElementById('ib2').style.backgroundImage = "url('<?php print $iconsPath;?>/" + getUrlByMode(2) + "')";
                    document.getElementById('ib3').className = 'icon ib2';
                    document.getElementById('ib3').style.backgroundImage = "url('<?php print $iconsPath;?>/" + getUrlByMode(3) + "')";

                    document.getElementById('ib7').className = 'icon ib2';
                    document.getElementById('ib7').style.backgroundImage = "url('<?php print $iconsPath;?>/" + getUrlByMode(7) + "')";

                    document.getElementById('selmodeid1').className = 'icon ib2';
                    document.getElementById('selmodeid2').className = 'icon ib2';
                    document.getElementById('selmodeid3').className = 'icon ib2';
                    document.getElementById('selmodeid7').className = 'icon ib2';

                    document.getElementById('asid2').className = '';
                    //document.getElementById('asid2').style.backgroundImage = '';

                    document.getElementById('asid4').className = 'icon ib';
                    updateRequestPassUi();
                    updateUseRmoteServerUI();
                    //document.getElementById('asid4').style.backgroundImage = 'url("<?php print $iconsPath;?>/clock.png")';
                } else  // remove "individual" icon
                {
                    document.getElementById('asid4').className = '';
                    //document.getElementById('asid4').style.backgroundImage = 'url("<?php print $iconsPath;?>/clock.png")';

                    document.getElementById('ib1').className = 'icon ib';
                    document.getElementById('ib2').className = 'icon ib';
                    document.getElementById('ib3').className = 'icon ib';
                    document.getElementById('ib7').className = '';

                    document.getElementById('selmodeid1').className = 'icon ib';
                    document.getElementById('selmodeid2').className = 'icon ib';
                    document.getElementById('selmodeid3').className = 'icon ib';
                    document.getElementById('selmodeid7').className = '';

                    document.getElementById('asid2').className = 'icon ib';
                    //document.getElementById('asid2').style.backgroundImage = 'url("<?php print $iconsPath;?>/t1.png")';
                }

                modal.style.display = "block";
            }

            var setTimeOutFlagTmp;
            var setTimeOutFlag = false;
            var timeOutModeTmp;
            var timeOutMode = 1;
            var curTimeOutTmp;
            var curTimeOut = 2;

            function addTimeOut(i1, i2, i3, prefix) {
                timeOutModeTmp = timeOutModeTmp + i3;

                if (timeOutModeTmp < 0)
                    timeOutModeTmp += 3;

                if (timeOutModeTmp > 3)
                    timeOutModeTmp -= 3;

                timeOutModeTmp = timeOutModeTmp % 3;

                curTimeOutTmp += i1 + i2;

                //alert(curTimeOutTmp);

                if (curTimeOutTmp > 99)
                    curTimeOutTmp -= 99;

                if (curTimeOutTmp < 1)
                    curTimeOutTmp += 99;

                //alert(curTimeOutTmp);

                var time = 'фиг';

                if (timeOutModeTmp == 0)
                    time = 'минут';

                if (timeOutModeTmp == 1)
                    time = 'часов';

                if (timeOutModeTmp == 2)
                    time = 'дней';

                document.getElementById(prefix + 'time05').innerHTML = time;

                var temp = curTimeOutTmp;

                var d = temp % 10;
                document.getElementById(prefix + 'time1').innerHTML = d;

                temp = Math.floor(temp / 10);
                d = temp % 10;
                document.getElementById(prefix + 'time10').innerHTML = d;

                updateTimeOutDate();
            }

            function showHideTimeOutUI() {
                // set true of false image for checkbox
                var link = 'check-false.png';
                if (setTimeOutFlagTmp)
                    link = 'check-true.png';
                document.getElementById('setTimeOutId').style.backgroundImage = "url('<?php print $iconsPath;?>/" + link + "')";

                if (setTimeOutFlagTmp) {
                    document.getElementById('timoutSettingsId').style.display = 'block';
                    modal3.style.paddingTop = '20px';
                } else {
                    document.getElementById('timoutSettingsId').style.display = 'none';
                    modal3.style.paddingTop = '100px';
                }
            }

            function setTimeOut() {
                setTimeOutFlagTmp = !setTimeOutFlagTmp;

                showHideTimeOutUI();
            }

            // ============== ROOM NUM SETTINGS - START

            var curT = 0;
            var curN = 0;
            var curP = 0;

            var currentY = 0;
            var dec = 0;

            var killIntervalId = 0;

            function copy() {
                document.getElementById('roomNameStr7').innerHTML = '<b>' + document.getElementById('room-name-text').value + '</b>';
            }

            function decY() {
                currentY += dec;
                document.getElementById('myModal8').style.paddingTop = currentY + 'px';

                if (currentY > 20 && dec < 0)
                    setTimeout(decY, 5);

                if (currentY < 100 && dec > 0)
                    setTimeout(decY, 5);
            }

            function focusFunction() {
                return;

                killIntervalId = setInterval(copy, 200);

                currentY = 100;//parseInt(document.getElementById('myModal8').style.paddingTop);
                dec = -2;
                decY();
            }

            function blurFunction() {
                return;

                clearInterval(killIntervalId);

                currentY = 20;
                //dec = 2;
                //decY();
            }

            function shiftN(inc) {
                curN += inc;
                if (curN < 0)
                    curN = 16;
                curN = curN % 17;

                document.getElementById('digitN').innerHTML = curN;
            }


            function shiftPs(inc) {

                curP += inc;
                if (curP < 1)
                    curP = 16;

                curP = curP % 17;

                if (curP == 0)
                    curP = 1;

                //alert(curP);

                document.getElementById('digitP').innerHTML = curP;
            }

            function shiftTs(inc) {

                curT += inc;
                if (curT < 0)
                    curT = 15;
                curT = curT % 16;

                document.getElementById('digitT').innerHTML = roomsTsensorsNames[curT];
            }

            function roomNumSettingsClick() {
                document.getElementById('myModal8').style.paddingTop = '20px';
                document.getElementById('myModal8').style.display = "block";
                document.getElementById('room-name-text').value = roomsName[roomIdSelected];


                if (roomIdSelected == 0) {
                    document.getElementById('roomsNumId').style.display = "block";
                    document.getElementById('roomsNumId2').style.display = "block";
                    document.getElementById('roomTsensorId').style.display = "none";
                    document.getElementById('roomPOutputId').style.display = "none";

                } else {
                    document.getElementById('roomsNumId').style.display = "none";
                    document.getElementById('roomsNumId2').style.display = "none";
                    document.getElementById('roomTsensorId').style.display = "block";
                    document.getElementById('roomPOutputId').style.display = "block";
                }

                //alert( $("#myModal8").outerHeight() );

                //var myDiv = document.getElementById("myModal8c");
                //alert( myDiv.clientHeight + " " + myDiv.scrollHeight + " " + myDiv.offsetHeight );

                curT = roomsTsensors[roomIdSelected];
                curN = roomsNum;
                curP = roomsPOutputs[roomIdSelected];

                shiftTs(0);
                shiftN(0);
                shiftPs(0);
            }

            function updateRoomNames() {
                for (var i = 0; i < 17; i++)
                    document.getElementById('rnId' + i).innerHTML = roomsName[i];
            }

            function updateNumberOfRooms() {
                for (var i = 0; i <= roomsNum; i++)
                    document.getElementById('myBtn' + i).style.display = "block";


                for (var i = roomsNum + 1; i < 17; i++)
                    document.getElementById('myBtn' + i).style.display = "none";
            }

            function escapeHtml(text) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };

                return text.replace(/[&<>"']/g, function (m) {
                    return map[m];
                });
            }

            function escapeHtml2(text) {
                var map = {
                    '&': '',
                    '<': '',
                    '>': '',
                    '"': '',
                    "'": ''
                };

                return text.replace(/[&<>"']/g, function (m) {
                    return map[m];
                });
            }

            function applyRoomNum() {
                var wasChanges = false;
                var wasChanges2 = false;


                if (roomIdSelected == 0 && resetRoomSettingsVar) {  /*
      myConfirm("Все насстройки комнат будут сброшены. Продолжить?","Сброс настроек...",'');

      while( !myConfirmDone )
      {

          //console.log('sleep');
          alert('wait');
      }

      alert('done');
      if ( myConfirmAnswer )
        resetRoomSettingsFunc();

      */
                }

                if (roomIdSelected == 0) {
                    passw = document.getElementById('room-passw-text').value;
                    text = escapeHtml(document.getElementById('room-passw-text').value);

                    if (text != passw) {
                        myAlert("Недопустимые символы & < > ' в пароле", "Ошибка", '<?php print $iconsPath;?>/s-fail.png');
                        return;
                    }

                    if (text != "") {
                        res = confirm("Сменить ваш пароль на новый: " + text);

                        if (res) {
                            changePassword(text);
                            return;
                        }
                    }
                }

                var roomNameTmp = roomsName[roomIdSelected];
                var roomTSensorTmp = roomsTsensors[roomIdSelected];
                var roomPOutputTmp = roomsPOutputs[roomIdSelected];
                var roomsNumTmp = roomsNum;

                if (document.getElementById('room-name-text').value != roomsName[roomIdSelected]) {
                    roomsName[roomIdSelected] = escapeHtml(document.getElementById('room-name-text').value);
                    wasChanges = true;
                }

                if (curT != roomsTsensors[roomIdSelected]) {
                    roomsTsensors[roomIdSelected] = curT;
                    wasChanges2 = true;
                    //alert(roomIdSelected+" "+curT);
                }

                if (curP != roomsPOutputs[roomIdSelected]) {
                    roomsPOutputs[roomIdSelected] = curP;
                    wasChanges2 = true;
                }

                if (curN != roomsNum) {
                    roomsNum = curN;
                    wasChanges = true;
                }


                var doneOK = true;
                var doneOK2 = true;

                // save room T-sensor
                if (wasChanges2)
                    doneOK2 = saveSettings(roomIdSelected, true, false);

                if (wasChanges) {
                    doneOK = saveRooms(roomIdSelected, true);
                    if (!wasChanges2)
                        doneOK2 = saveSettings(roomIdSelected, true, false);

                    thisServerUpdateTime = Math.floor(Date.now() / 1000);
                    //alert(modeXWebUIDate);
                    //saveServerStr(0,modeXStrPrev+";"+shortDate(modeXControllerDate)+";"+shortDate(modeXWebUIDate));
                    //saveServerStr(0,modeXStrPrev+";"+modeXControllerDate+";"+modeXWebUIDate);
                }

                if (doneOK && doneOK2) {
                    document.getElementById('rnId' + roomIdSelected).innerHTML = roomsName[roomIdSelected];

                    updateRoomNameStr(roomsName[roomIdSelected]);
                    updateNumberOfRooms();

                    closeModal('8');

                    // only here 5
                    updateSettings[roomIdSelected] = 1;
                } else {
                    roomsName[roomIdSelected] = roomNameTmp;
                    roomsTsensors[roomIdSelected] = roomTSensorTmp;
                    roomsPOutputs[roomIdSelected] = roomPOutputTmp;
                    roomsNum = roomsNumTmp;
                }

            }

            // ============== ROOM NUM SETTINGS - END

            var curTemp = 22.5;

            function addTemp(i1, i2, i3, prefix) {
                curTemp += i1;
                curTemp += i2;
                curTemp += i3;

                if (curTemp < 0)
                    curTemp += 100;

                if (curTemp > 100)
                    curTemp -= 100;

                updateCurrentTemp(curTemp, prefix);
            }

            function updateCurrentTemp(temp, prefix) {
                temp = temp * 10;

                var d = temp % 10;
                document.getElementById(prefix + 'digit05').innerHTML = '.' + d;

                temp = Math.floor(temp / 10);
                d = temp % 10;
                document.getElementById(prefix + 'digit1').innerHTML = d;

                temp = Math.floor(temp / 10);
                d = temp % 10;
                document.getElementById(prefix + 'digit10').innerHTML = d;
            }

            // ========================= ADVANCED SETTINGS ================= START

            function roomAdancedSettingsClick() {
                curTemp = standByTemp[roomIdSelected];
                updateCurrentTemp(curTemp, "__");
                advancedSettingsModal.style.display = "block";
            }

            function applyRoomAdvancedSettings() {
                var haveModeOrTemprChanges = false;

                if (standByTemp[roomIdSelected] != curTemp) {
                    standByTemp[roomIdSelected] = curTemp;
                    haveModeOrTemprChanges = true;
                }

                var doneOK = true;

                if (haveModeOrTemprChanges) {
                    // only here 2
                    updateSettings[roomIdSelected] = 1;
                    doneOK = saveSettings(roomIdSelected, true, false);
                }

                if (doneOK)
                    advancedSettingsModal.style.display = "none";
            }

            // ========================= ADVANCED SETTINGS ================= END

            // ========================= SCHEDULE MODE ===================== START
            var curTimeH = 23;
            var curTimeM = 59;

            function addTime(h10, h1, m10, m1) {
                curTimeH += h10 * 10;
                curTimeH += h1;

                if (curTimeH > 23)
                    curTimeH = 0;

                if (curTimeH < 0)
                    curTimeH = 23;

                curTimeM += m10 * 10;
                curTimeM += m1;

                if (curTimeM > 59)
                    curTimeM = 0;

                if (curTimeM < 0)
                    curTimeM = 59;

                updateCurrentTime(curTimeH, curTimeM);
            }

            function updateCurrentTime1(HHMM) {
                var m = HHMM % 100;
                h = Math.floor(HHMM / 100);
                //updateCurrentTime(h,m);

                if (m < 10)
                    m = '0' + m;
                if (h < 10)
                    h = '0' + h;

                document.getElementById('currentTimeId1').innerHTML = h + ":" + m;
            }

            function updateCurrentTime2(HHMM) {
                var m = HHMM % 100;
                h = Math.floor(HHMM / 100);
                updateCurrentTime(h, m);

                curTimeH = h;
                curTimeM = m;
            }

            function updateCurrentTime(HH, MM) {
                var m = MM;
                if (MM * 1 < 10)
                    m = '0' + MM;

                var h = HH;
                if (HH * 1 < 10)
                    h = '0' + HH;

                document.getElementById('currentTimeId2').innerHTML = h + ":" + m;

                var d = HH % 10;
                document.getElementById('digitH1').innerHTML = d;

                HH = Math.floor(HH / 10);
                d = HH % 10;
                document.getElementById('digitH10').innerHTML = d;

                d = MM % 10;
                document.getElementById('digitM1').innerHTML = d;

                MM = Math.floor(MM / 10);
                d = MM % 10;
                document.getElementById('digitM10').innerHTML = d;
            }


            var editScheduleTimeIntervalNum = 0;
            var scheduleArrTimeTmp = [0, 0, 0, 0, 0, 0];
            var scheduleArrTempTmp = [0, 0, 0, 0, 0, 0];
            var scheduleArrIntervalModeTmp = [0, 0, 0, 0, 0, 0];

            function updateScheduleTimeLabels() {
                document.getElementById('timeStart1').innerHTML = "00:00";
                document.getElementById('timeStart1').style.color = '#939393';
                document.getElementById('timeStop6').innerHTML = "23:59";
                document.getElementById('timeStop6').style.color = '#939393';

                for (var i = 1; i < 6; i++)
                    if (scheduleArrTimeTmp[i - 1] > scheduleArrTimeTmp[i])
                        scheduleArrTimeTmp[i] = scheduleArrTimeTmp[i - 1] + 1;


                for (var i = 1; i < 7; i++) {
                    if (i < scheduleNum + 1) {
                        var time = scheduleArrTimeTmp[i - 1];
                        var m = time % 100;
                        //var m2 = m+1;

                        h = Math.floor(time / 100);
                        //h2 = h;

                        /*if ( m2 == 60 )
        {
          m2 = 0;
          h2++;
        } */

                        if (m < 10)
                            m = '0' + m;
                        //if ( m2 < 10 )
                        //  m2 = '0' + m2;

                        if (h < 10) {
                            h = '0' + h;
                            //h2 = '0' + h2;
                        }

                        if (i == scheduleNum) {
                            document.getElementById('timeStop' + i).innerHTML = '23:59';
                            document.getElementById('timeStop' + i).style.color = '#939393';
                        } else if (i != 6) {
                            document.getElementById('timeStop' + i).innerHTML = h + ":" + m;
                            document.getElementById('timeStop' + i).style.color = '#fff';
                        }

                        if (i != 6) {
                            //var m2 = m + 1;
                            document.getElementById('timeStart' + (i + 1)).innerHTML = h + ":" + m;
                            document.getElementById('timeStart' + (i + 1)).style.color = '#939393';
                        }

                        var mode = scheduleArrIntervalModeTmp[i - 1];
                        if (mode == 0) // temperature
                        {
                            document.getElementById('tempInt' + i).innerHTML = scheduleArrTempTmp[i - 1] + '&deg;c';
                            document.getElementById('tempMode' + i).style.backgroundImage = 'url("<?php print $iconsPath;?>/t-small.png")';
                        } else if (mode == 1) // turn on
                        {
                            document.getElementById('tempMode' + i).style.backgroundImage = 'url("<?php print $iconsPath;?>/s_mode-on.png")';
                            document.getElementById('tempInt' + i).innerHTML = 'вкл';
                        } else
                            //if ( mode == 2 ) // turn off
                        {
                            document.getElementById('tempMode' + i).style.backgroundImage = 'url("<?php print $iconsPath;?>/s_mode-off.png")';
                            document.getElementById('tempInt' + i).innerHTML = 'выкл';
                        }

                        document.getElementById('interval' + i).style.display = 'block';
                    } else {
                        document.getElementById('interval' + i).style.display = 'none';
                    }
                }
            }

            function changeShdeuleMode(mode) {
                var d1 = 'block';
                var d2 = 'block';
                var d3 = 'block';

                if (mode == 0) {
                    d2 = 'none';
                    d3 = 'none';
                } else if (mode == 1) {
                    d1 = 'none';
                    d3 = 'none';
                } else {
                    d1 = 'none';
                    d2 = 'none';
                    mode = 2;
                }

                scheduleArrIntervalModeTmp[editScheduleTimeIntervalNum] = mode;

                document.getElementById('scheduleChangeTempId').style.display = d1;
                document.getElementById('scheduleTurnOnId').style.display = d2;
                document.getElementById('scheduleTurnOffId').style.display = d3;
            }

            function coppyArray2(Arr1, Arr2) {
                // this function only for reloadUI2!!!
                // because it skips the last one element (empty)

                /*var str = "";
    for ( var i = 0; i < Arr2.length-1; i++ )
    {
       str += Arr1[i]+"\n";
    }

    alert(str); */

                for (var i = 0; i < Arr2.length - 1; i++) {
                    for (var j = 0; j < Arr2[i].length; j++)
                        Arr1[i][j] = Arr2[i][j] * 1;
                }

                /*str = "";
    for ( var i = 0; i < Arr2.length-1; i++ )
    {
       str += Arr1[i]+"\n";
    }

    alert(str); */
            }

            function coppyArray_int(Arr1, Arr2) {
                for (var i = 0; i < Arr2.length; i++)
                    Arr1[i] = Arr2[i] * 1;
            }

            function coppyArray(Arr1, Arr2) {


                /*var areEqual = true;
    for ( var i = 0; i < Arr2.length; i++ )
      if ( Arr1[i] != Arr2[i] )
      {
        areEqual = false;
        break;
      }

    if ( areEqual )
    {
      alert("Equal:\n"+Arr1+'\n'+Arr2);
      return;
    } */

                //alert(Arr1);
                for (var i = 0; i < Arr2.length; i++)
                    Arr1[i] = Arr2[i];

                //alert(Arr1);
            }

            function scheduleClick() {
                scheduleNum = scheduleIntervalsNum[roomIdSelected];

                coppyArray(scheduleArrTimeTmp, scheduleArrTime[roomIdSelected]);
                coppyArray(scheduleArrTempTmp, scheduleArrTemp[roomIdSelected]);
                coppyArray(scheduleArrIntervalModeTmp, scheduleArrIntervalMode[roomIdSelected]);

                updateScheduleTimeLabels();
                // ложный dirty flag
                if (scheduleNum >= 6) {
                    document.getElementById('addScheduleId').style.display = 'none';
                    document.getElementById('deleteButtonBottomId').style.backgroundImage = 'url("<?php print $iconsPath;?>/del-btn.png")'; //'block';
                } else {
                    document.getElementById('addScheduleId').style.display = 'block';
                    document.getElementById('deleteButtonBottomId').style.backgroundImage = 'url("<?php print $iconsPath;?>/empty35.png")';
                }


                modal2.style.display = "block";
            }

            function HaveChangesInArrays(Arr1, Arr2) {
                //alert( Arr1 + ":" + Arr2 + " len:" + Arr1.length );

                for (var i = 0; i < Arr1.length; i++)
                    if (Math.abs(Arr1[i] - Arr2[i]) > 0.001)
                        return true;

                return false;
            }

            var haveScheduleChanges = false;

            function applyScheduleSettings() {
                if (HaveChangesInArrays(scheduleArrTime[roomIdSelected], scheduleArrTimeTmp))
                    haveScheduleChanges = true;

                if (scheduleIntervalsNum[roomIdSelected] != scheduleNum)
                    haveScheduleChanges = true;

                if (HaveChangesInArrays(scheduleArrTemp[roomIdSelected], scheduleArrTempTmp))
                    haveScheduleChanges = true;

                if (HaveChangesInArrays(scheduleArrIntervalMode[roomIdSelected], scheduleArrIntervalModeTmp))
                    haveScheduleChanges = true;

                var doneOK = true;

                var scheduleArrTimeTmp2 = scheduleArrTime[roomIdSelected];
                var scheduleNum2 = scheduleIntervalsNum[roomIdSelected];
                var scheduleArrTempTmp2 = scheduleArrTemp[roomIdSelected];
                var scheduleArrIntervalModeTmp2 = scheduleArrIntervalMode[roomIdSelected];

                //alert( haveScheduleChanges );
                //alert( scheduleArrTemp[roomIdSelected] + "\n" + scheduleArrTempTmp + "\n" + haveScheduleChanges );

                if (haveScheduleChanges) {
                    scheduleIntervalsNum[roomIdSelected] = scheduleNum;

                    coppyArray(scheduleArrTime[roomIdSelected], scheduleArrTimeTmp);
                    coppyArray(scheduleArrTemp[roomIdSelected], scheduleArrTempTmp);
                    coppyArray(scheduleArrIntervalMode[roomIdSelected], scheduleArrIntervalModeTmp);

                    //alert(roomIdSelected+"|"+scheduleArrIntervalMode[roomIdSelected]);
                    doneOK = saveSettings(roomIdSelected, true, false);
                }

                if (doneOK) {
                    closeModal('2');

                    if (haveScheduleChanges)     // only here 3
                        updateSettings[roomIdSelected] = 1;

                    haveScheduleChanges = false;
                } else {
                    coppyArray(scheduleArrTime[roomIdSelected], scheduleArrTimeTmp2);
                    scheduleIntervalsNum[roomIdSelected] = scheduleNum2;
                    coppyArray(scheduleArrTemp[roomIdSelected], scheduleArrTempTmp2);
                    coppyArray(scheduleArrIntervalMode[roomIdSelected], scheduleArrIntervalModeTmp2);
                }
            }

            function scheduleChangeTimeClick(id) {
                if (scheduleNum == 1 || id == scheduleNum)
                    return;

                // edit time interval
                editScheduleTimeIntervalNum = id - 1;
                var time = 0;
                if (id > 1)
                    time = scheduleArrTimeTmp[id - 2];
                updateCurrentTime1(time);
                updateCurrentTime2(scheduleArrTimeTmp[id - 1]);
                scheduleTimeDialog.style.display = "block";

            }

            function applyScheduleTime() {
                // aply time interval
                scheduleTimeDialog.style.display = "none";
                var id = roomIdSelected;

                scheduleArrTimeTmp[editScheduleTimeIntervalNum] = curTimeH * 100 + curTimeM;
                updateScheduleTimeLabels();
            }

            function changeScheduleTemp(id) {
                // edit temperature for time interval
                scheduleTempDialog.style.display = "block";
                curTemp = scheduleArrTempTmp[id - 1];
                updateScheduleTimeLabels();
                editScheduleTimeIntervalNum = id - 1;

                changeShdeuleMode(scheduleArrIntervalModeTmp[id - 1]);
                updateCurrentTemp(curTemp, '_');
            }

            function applyScheduleChangeTemp() {
                // apply temperature for time interval
                scheduleTempDialog.style.display = "none";

                scheduleArrTempTmp[editScheduleTimeIntervalNum] = curTemp;

                updateScheduleTimeLabels();
            }

            var myConfirmAnswer = false;
            var myConfirmDone = false;

            function okCancelConfirm(confirm) {
                document.getElementById('myModalMSG-confirm').style.display = 'none';
                myConfirmAnswer = confirm;
                myConfirmDone = true;
            }

            function myConfirm(msg, caption, img_url) {
                myConfirmAnswer = false;
                myConfirmDone = false;

                document.getElementById('msg-icon-id-confirm').style.backgroundImage = 'url("' + img_url + '")';

                document.getElementById('_msg-capid-confirm').innerHTML = caption;
                document.getElementById('_msg-text-id-confirm').innerHTML = msg;
                document.getElementById('_msg-text-div-confirm').style.display = 'block';

                document.getElementById('myModalMSG-confirm').style.display = 'block';
            }

            function myAlert(msg, caption, img_url) {
                document.getElementById('msg-icon-id').style.backgroundImage = 'url("' + img_url + '")';

                //document.getElementById('okButtonId').style.display = 'block';
                //document.getElementById('msgcapid').innerHTML = caption;
                document.getElementById('_msg-capid').innerHTML = caption;
                document.getElementById('_msg-text-id').innerHTML = msg;
                document.getElementById('_msg-text-div').style.display = 'block';

                document.getElementById('myModalMSG').style.display = 'block';
            }

            var clipBoardId = "-";

            function copySchedule() {
                var time = 600;
                if (clipBoardId == "-")
                    time = 0;

                clipBoardId = roomIdSelected;
                document.getElementById('pasteButtonId').innerHTML = "";

                setTimeout(function () {
                    document.getElementById('pasteButtonId').innerHTML = '<b>' + clipBoardId + '<b>';
                }, time);
            }

            function pasteSchedule() {
                if (clipBoardId == "-" && clipBoardId != 0)
                    myAlert("Нечего копировать...", "Копия расписания...", 'icons/paste_20.png');
                else {
                    scheduleNum = scheduleIntervalsNum[clipBoardId];

                    if (isNumeric(scheduleNum)) {
                        for (i = 0; i < 6; i++) {
                            scheduleArrTimeTmp[i] = scheduleArrTime[clipBoardId][i];
                            scheduleArrTempTmp[i] = scheduleArrTemp[clipBoardId][i];
                            scheduleArrIntervalModeTmp[i] = scheduleArrIntervalMode[clipBoardId][i];
                        }

                        updateScheduleTimeLabels();
                        myAlert("Скопировано из '" + roomsName[clipBoardId] + "'<br> в '" + roomsName[roomIdSelected] + "'", "Копия расписания...", 'icons/paste_20.png');
                    }
                }
            }


            var showAdvancedScheduleUI = false;

            function advancedSettingsScheduleClickUpdateUI() {
                if (showAdvancedScheduleUI) {
                    document.getElementById('copyPasteId').style.display = 'block';
                    if (scheduleNum < 6)
                        document.getElementById('addScheduleId').style.display = 'block';
                    document.getElementById('advancedSettingsScheduleId').style.backgroundImage = 'url("<?php print $iconsPath;?>/menu-up.png")';
                } else {
                    document.getElementById('copyPasteId').style.display = 'none';
                    document.getElementById('addScheduleId').style.display = 'none';
                    document.getElementById('advancedSettingsScheduleId').style.backgroundImage = 'url("<?php print $iconsPath;?>/menu-down.png")';
                }
            }

            function advancedSettingsScheduleClick() {
                showAdvancedScheduleUI = !showAdvancedScheduleUI;

                advancedSettingsScheduleClickUpdateUI();
            }

            var scheduleNum = 3;

            function addScedule() {
                if (scheduleNum < 6)
                    scheduleNum++;

                var id = scheduleNum;

                if (scheduleNum < 7) {
                    document.getElementById('interval' + id).style.display = 'block';
                    //scheduleArrTime[roomIdSelected][id] = 700;
                }

                if (scheduleNum >= 6) {
                    document.getElementById('addScheduleId').style.display = 'none';
                    //document.getElementById('deleteButtonBottomId').style.display = 'block';
                    document.getElementById('deleteButtonBottomId').style.backgroundImage = 'url("<?php print $iconsPath;?>/del-btn.png")'; //'block';
                }

                updateScheduleTimeLabels();
            }

            function deleteSchedule(index) {

                if (index == 2 && scheduleNum < 6) // we are clicking 'empty35' button
                    return;

                var id = scheduleNum;

                if (scheduleNum > 1) {
                    document.getElementById('interval' + id).style.display = 'none';

                    if (scheduleNum < 7) {
                        document.getElementById('addScheduleId').style.display = 'block';
                        //document.getElementById('deleteButtonBottomId').style.display = 'none';
                        document.getElementById('deleteButtonBottomId').style.backgroundImage = 'url("<?php print $iconsPath;?>/empty35.png")';
                    }
                    scheduleNum--;
                }

                updateScheduleTimeLabels();
            }

            // ========================= SCHEDULE MODE ===================== STOP

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

        <div id='inp'></div>

    </body>
</html>

<?php
function getModeImg($mode)
{
    if ($mode == 1)
        return 'mode-hand.png';
    if ($mode == 2)
        return 'mode-schedule.png';
    if ($mode == 3)
        return 'mode-standby.png';
    if ($mode == 4)
        return 'mode-on.png';
    if ($mode == 5)
        return 'mode-off.png';
    if ($mode == 7)
        return 'individual.png';
}

function printBlock($id)
{
    global $roomsName;
    global $currentMode;
    global $roomsTsensors;
    global $roomsNum;
    global $iconsPath;

    $modeImgLink = getModeImg($currentMode[$id]);

    //if ( $id > 0 )
    $Tid = $id;//$roomsTsensors[$id-1];

    $display = "";
    if ($id > $roomsNum)
        $display = "display: none;";

    print "<div id='myBtn$id' class='bgcolor' style='$display height: 80px; line-height: 14px'>";
    print "<div style='width: 66%; float: left'>";
    print "<div style='line-height: 15px'>";

    $url = 'empty20.png';
    $chat = "";
    if ($id == 0) {
        print "<div id='Tx_$Tid' style='font-family: lcd; font-size: x-large; float: left; width: 46%'>--:--</div>";
        $chat = "id='chatIconId' onclick='showChat()'";
        $url = 's-chat.png';
    } else
        print "<div id='Tx_$Tid' style='float: left; width: 40%'>--&deg;c</div>";

    print "<div class='icon' $chat style='height: 25%; width: 18%; background-image: url(\"$iconsPath/$url\")'></div>";
    print "<div id='updateId$id' class='icon' style='height: 25%; width: 18%; background-image: url(\"$iconsPath/empty20.png\")'></div>";
    print "<div id='allHouseId$id' class='icon' style='height: 25%; width: 18%; background-image: url(\"$iconsPath/empty20.png\")'></div>";
    print "</div>";
    print "<div id='rnId$id' style='line-height: 14px'>$roomsName[$id]</div>";
    print "</div>";
    print "<div onclick='roomRightNowClcik($id)' class='icon' style='width: 7%;background-image: url(\"$iconsPath/t1.png\"); height: 90%'></div>";
    print "<div style='width: 5%; float: left; height: 90%'>";
    print "<div id='desiredTempId$id' style='line-height: 16px; font-size:small'>--&deg;с</div>";
    print "<div style='line-height: 29px'></div>";
    print "</div>";
    print "<div onclick='roomClick($id)' style='width: 21%; float: left'>";
    print "<div class='icon' id='hs$id' style='height: 90%'></div>";
    print "<div id='roomModeId$id' class='icon' style='background-image: url(\"$iconsPath/$modeImgLink\"); height: 90%'></div>";
    print "</div>";
    print "</div>";
}

function printScheduleSection($id)
{
    global $iconsPath;
    //global $scheduleIntervalsNum;

    $disp = 'none';
    //if ( $id < $scheduleIntervalsNum[$id] + 1 )
    // $disp = 'block';

    print "<div class='expand-animation' id='interval$id' style='height:45px; display: $disp'>";
    print "   <div class='icon' style='width: 15%; background-image: url(\"$iconsPath/mode-schedule.png\")'></div>";
    print "   <div class='icon' style='width: 5%;  line-height:55px; height:40px;font-weight:bold;color:white'>$id</div>";
    print "   <div id='timeStart$id' onclick='scheduleChangeTimeClick($id)' class='icon scheduleTime' style='width: 23%'>00:00</div>";
    print "   <div onclick='scheduleChangeTimeClick($id)' class='icon scheduleTime' style='width: 5%'>-</div>";
    print "   <div id='timeStop$id' onclick='scheduleChangeTimeClick($id)' class='icon scheduleTime' style='width: 23%'>23:59</div>";
    print "   <div onclick='changeScheduleTemp($id)'class='icon'   style='width: 27%; color:white'>";
    print "     <div id='tempMode$id' class='icon' style='width: 31%; background-image: url(\"$iconsPath/t-small.png\")'></div>";
    print "     <div id='tempInt$id' class='icon' style='width: 69%; line-height: 33px; height: 40px; font-size: small;'>--&deg;c</div>";
    print "   </div>";
    print " </div>";
}

?>
 