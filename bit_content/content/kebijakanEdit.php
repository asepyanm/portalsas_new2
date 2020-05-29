<?
session_start();
include_once("../../bit_config.php");
?>
<html>

<head>
    <title><? echo $bit_app["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"] ?>bit_css/standart.css">
    <script type="text/javascript" src="../../bit_mod/mod_js.js"></script>
    <script>
        function validate() {

            if (document.forms[0].tJudul.value == '') {
                alert('Judul silahkan diisi terlebih dahulu !');
                document.forms[0].tJudul.focus();
                return false;
            }

            if (document.forms[0].tFile.value != '') {
                if (checkFileExtensionPdf(document.forms[0].tFile) != 1) {
                    alert("checkFileExtensionPdf(document.forms[0].tFile)");
                    return "false";
                }
            }

            return true;
        }
    </script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" onLoad="document.forms[0].tInput.focus()">
    <?

    $f = new clsForm;
    $f->openForm("frmMain", "frmMain", "", "POST", "multipart/form-data", "onSubmit='return validate()'");

    $ora = new clsMysql;
    $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

    ?>
    <table width="100%">
        <tr>
            <td colspan="2">
                <? echo setTitle2("Upload File Peraturan Perundang undangan"); ?>
            </td>
        </tr>

        <?php
        $ora = new clsMysql;
        $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);
        $qry = "select * from m_kebijakan where id=" . $_GET["id"];
        $rs = $ora->sql_fetch($qry, $bit_app["db"]);

        if (!$_POST["ok"]) {
            $_POST["id"] = $rs->value[1][1];
            $_POST["tJudul"] = $rs->value[1][2];
            $file["tFile"] = $rs->value[1][3];
            $_POST["tInput"] = $rs->value[1][4];
        }

        ?>
        <tr>
            <input type="hidden" name="id" value="<?= $_POST['id']; ?>">
            <td width="30%">Judul *</td>
            <td><? $f->textbox("tJudul", "tJudul", $_POST["tJudul"], "inputBox", 50, 255) ?></td>
        </tr>
        <tr>
            <td valign="top">File <b>*</b> (.pdf) </td>
            <td>
                <? $f->file("tFile", "tFile", "browse", "inputBox", 50, 255, "
				if (checkFileExtensionPdf(this)!=1) {
					alert(checkFileExtensionPdf(this))
                }") ?>
                File sebelumnya : <a href="<?= $bit_app["path_url"] . 'bit_mod/showdoc.php?target=' .  $file['tFile']; ?>" target="_blank"><?= $file['tFile']; ?></a>
                <input type="hidden" name="hiddenFile" value="<?= $file['tFile']; ?>">
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top">Keterangan</td>
            <td>
                <?
                $f->textarea("tInput", "tInput", $_POST["tInput"], "inputBox", 6, 60);
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr class="article_layout_hr">
                <b>Ket :</b>
                Field yang bertanda (*) harus diisi.
                <hr class="article_layout_hr">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <? $f->submit("ok", "Simpan", "button") ?>
                <? $f->button("reset", "Close", "button", "window.close()") ?>
            </td>
        </tr>
    </table>
    <?
    $f->closeForm();

    if ($_POST["ok"]) {

        $ora = new clsMysql;
        $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

        if ($_FILES['tFile']['name'] != null) {
            $img1 = do_upload_limit("tFile", 10000000);
        } else {
            $img1 = $_POST['hiddenFile'];
        }
        $qry = "UPDATE m_kebijakan SET judul = '" . $_POST['tJudul'] . "', nama_file = '" . $img1 . "', keterangan = '" . $_POST['tInput'] . "', created_by = " . getUserID() . ", created_date = '" . date('Y-m-d') . "' WHERE id = " . $_POST['id'] . "";

        if (!$ora->sql_no_fetch($qry, $bit_app["db"])) {
            alert($qry);
            $ora->logoff();
        } else {
            parent_opener_submit();
            $ora->logoff();
            close();
        }
    }
    ?>
</body>

</html>