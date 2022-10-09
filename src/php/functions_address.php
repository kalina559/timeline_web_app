<?php
    header('Content-Type: application/json');
    $aResult = array();

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'login':
                $aResult['result'] = 'invoked login';
               break;

            default:
            $aResult['result'] = 'nope';
            break;
        }

    }
    echo json_encode($aResult['result']);

?>