<?php

require_once '../lib/includes/includes.inc.php';

$kwixo = new Kwixo();

$md5 = new HashMD5();

//checks security
$waitedhash = $md5->hash($kwixo->getAuthKey() . $_POST['RefID'] . $_POST['TransactionID']);
if ($_POST['HashControl'] != $waitedhash)
    die('HashControl non validé');


//action depending on the tag value
switch ($_POST['Tag']) {
    case '0':
        /*
         * Paiement avorté entraînant logiquement l’annulation de la commande (Attention : envoi une heure après pour être sûr de l’état)
         * vérifier Tag actuellement enregistré, ne rien faire si différent de 4 ou 0
         */
        break;

    case '1':
        /*
         * OK Paiement accepté et commande validée (vous pouvez confirmer la transaction pour la
         * financer et livrer). Un attribut « Score » complète ce tag. Il peut prendre les valeurs « positif »
         * ou « negatif » en fonction de l’évaluation automatique de notre système
         */
        break;

    case '2':
        /*
         * KO Paiement refusé entraînant logiquement l’annulation de la commande (refus de
         * paiement ou refus crédit ou tentative de fraude avérée)
         */
        break;

    case '3':
        /*
         * SU Commande sous surveillance FIA-NET. Vous pouvez soit attendre l’évolution du tag :
         * les équipes FIA-NET vont procéder à une étude plus poussée de la commande qui aboutira à
         * une évolution de son état (cf. zone surveillance ci-dessous), soit décider de confirmer la
         * transaction immédiatement (dans ce cas, la commande n’est pas garantie par FIA-NET). Aussi,
         * nous vous conseillons d’attendre la réponse de notre service « Contrôle Expert ».
         */
        break;

    case '4':
    case '5':
        /*
         * En attente d’un traitement interne, ne pas prendre en compte. Attendre l’évolution du tag.
         */
        break;

    case '6':
        /*
         * crédit à l'étude
         */
        break;

        case '10':
            /*
             * Surveillance OK, la commande est "libérée" et validée : vous pouvez livrer.
             */
            break;

        case '11':
            /*
             * Surveillance KO, le contrôle ne peut aboutir et le délai maximum (réglable) pour
             * confirmer la transaction malgré l'avis défavorable de FIA-NET (commande non garantie) est
             * dépassé. Le risque de fraude présenté par la commande peut être important. Vous ne pouvez
             * plus confirmer cette transaction.
             */
            break;

        case '12':
            /*
             * Surveillance KO, le Contrôle Expert a abouti à une évaluation défavorable. Nous
             * déconseillons la livraison. Néanmoins, vous pouvez décider de confirmer la transaction à ce
             * stade. Dans ce cas, la transaction n’est pas garantie par FIA-NET. Par défaut, ce tag n’est pas
             * envoyé. Pour le recevoir, rendez vous dans votre Back Office (rubrique Paramétrage > Retour
             * de script) ou contactez votre chargé d’affaires.
             * La réception de ce tag vous permettra de connaître au plus tôt l'évaluation défavorable de FIA-NET suite à
             * la surveillance, ce qui vous permettra dans le délai maximum (réglable) de venir tout de même confirmer
             * cette transaction.
             */
            break;

        case '13':
            /*
             * Commande confirmée par vos soins alors que la commande était sous surveillance FIANET.
             * Cette transaction n’est pas garantie par FIA-NET.
             */
            break;

        case '14':
            /*
             * Commande confirmée par vos soins alors que la commande avait fait l’objet d’un avis
             * défavorable de notre service « Contrôle Expert ». Cette transaction n’est pas garantie par FIANET.
             */
            break;

        case '100':
            /*
             * OK Clôture de la transaction (livraison effectuée et débit de votre client)
             */
            break;

        case '101':
            /*
             * KO Annulation de la transaction (avant ou après financement)
             */
            break;

    default:
        break;
}