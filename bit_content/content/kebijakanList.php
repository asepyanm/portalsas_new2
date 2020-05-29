<html>

<head>
    <title><? echo $bit_app["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"] ?>bit_css/standart.css">
    <?
    getCalendarModule();
    ?>
    <script>
        function validate() {
            if (document.forms[0].tInput[0].value == '') {
                alert('Judul silahkan diisi terlebih dahulu !');
                document.forms[0].tInput[0].focus();
                return false;
            }

            return true;
        }
    </script>
</head>

<body leftmargin="0" topmargin="0">
    <?

    $f = new clsForm;
    $f->openForm("frmMain", "frmMain", "", "POST", "multipart/form-data");

    $ora = new clsMysql;
    $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

    ?>
    <p align="left">
        <table cellpadding="1" cellspacing="1" border="0" width="100%">
            <tr>
                <td colspan="5">
                    <? echo setTitleCari("Peraturan Perundang Undangan"); ?>
                </td>
            </tr>
        </table>

        <table cellpadding="1" cellspacing="0">
            <tr>
                <td align="left">
                    <input type="text" name="tSearch" size="30" class="inputtext" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"];
                                                                                            else echo "Search Judul atau Nama File" ?>" onMouseOver="if (this.value=='Search Judul atau Nama File') this.value=''" onMouseOut="if (this.value=='') this.value='Search Judul atau Nama File'">
                </td>
                <td><input type="submit" class="button" onClick="document.forms[0].page.value=1" value="Search "></td>
            </tr>
        </table>
        <hr class="article_layout_hr">
        <table cellpadding="0" cellspacing="1" border="0" width="100%">
            <thead>
                <tr>
                    <? $arrField = array("NO", "Judul", "Nama File", "Keterangan", "Update By", "Tanggal Submit") ?>
                    <?
                    for ($i = 0; $i < count($arrField); $i++) {
                        echo "<th class='listTableHead'>" . $arrField[$i] . "&nbsp;&nbsp;";
                        if ($_POST["hdOrder"][$i] == 1)
                            $orderBy .= ($i + 1) . " asc,";
                        elseif ($_POST["hdOrder"][$i] == 2)
                            $orderBy .= ($i + 1) . " desc,";
                        echo "</th>";
                    }
                    echo "<th colspan=4 class='listTableHead'>Perintah</th>";
                    if ($orderBy)
                        $orderBy = "order by " . substr($orderBy, 0, strlen($orderBy) - 1);
                    ?>
                </tr>
            </thead>
            <tbody>
                <?

                if ($_POST["hdAction"]) {
                    $qry = "delete from m_kebijakan where id=" . (int) $_POST["id"] . "";
                    $ora->sql_no_fetch($qry, $bit_app["db"]);
                }


                if ($_POST["abj"]) {
                    $where .= " and upper(substring(nama_karyawan,1,1))='" . strtoupper($_POST["abj"]) . "'";
                }

                if ($_POST["tSearch"] && $_POST["tSearch"] != "Search Judul atau Nama File") {
                    $where .= " WHERE judul like '%" . strtoupper($_POST["tSearch"]) . "%' or nama_file like '%" . strtoupper($_POST["tSearch"]) . "%'";
                }


                $qry = "select 
					count(1) 
				  from m_kebijakan $where";
                $rs = $ora->sql_fetch($qry, $bit_app["db"]);
                $totalRow = $rs->value[1][1]; //total record	

                //==============================================
                $page = $_POST["page"];
                if ($page == 0)
                    $page = 1;


                if ($_POST["cPage"])
                    $page = (($_POST["slPage"] - 1) * $bit_app["sumOfPage"]) + 1;

                if (!$_POST["slPage"])
                    $_POST["slPage"] = 1;



                $rownum2 = ($page * $bit_app["sumOfRow"]) + 1;
                $rownum1 = ($page - 1) * $bit_app["sumOfRow"] + 1;

                $paging = ceil($totalRow / $bit_app["sumOfRow"]);
                //=============================================

                $qry = "select 
					* from m_kebijakan
				  		$where
				  limit " . ($rownum1 - 1) . "," . $bit_app["sumOfRow"] . "
				  ";
                $rs = $ora->sql_fetch($qry, $bit_app["db"]);

                for ($i = 1; $i <= $rs->jumrec; $i++) {
                    if ($i % 2 == 0)
                        $cl = "listTableRow";
                    else
                        $cl = "listTableRowS";

                ?>
                    <tr class="<? echo $cl ?>">
                        <td align="center"><? echo ((($page - 1) * $bit_app["sumOfRow"]) + $i) ?></td>
                        <td align="center"><? echo $rs->value[$i][2] ?></td>

                        <!--
                            <td align="center"><a href="<?= $bit_app["path_url"] . 'bit_folder/' . $rs->value[$i][3]; ?>" target="_blank"><?= $rs->value[$i][3]; ?></a></td>
                        //-->

                        <td align="center"><a href="<?= $bit_app["path_url"] . 'bit_mod/showdoc.php?target=' . $rs->value[$i][3]; ?>" target="_blank"><?= $rs->value[$i][3]; ?></a></td>
                        

                        <td align="center"><? echo $rs->value[$i][4] ?></td>
                        <?php $qry = "SELECT nama_karyawan from m_master_user WHERE nik = '" . $rs->value[$i][5] . "'";
                        $rj = $ora->sql_fetch($qry, $bit_app["db"]); ?>
                        <td align="center"><? echo $rj->value[1]['nama_karyawan'] ?></td>
                        <td align="center"><? echo $rs->value[$i][6] ?></td>
                        <td align="center">
                            <img title="Edit File" style="cursor:pointer" src="<? echo $bit_app["path_url"] ?>bit_images/edit.gif" onClick="var win; win=window.open('<? echo $bit_app["path_url"] ?>bit_content/content/kebijakanEdit.php?id=<? echo $rs->value[$i][1] ?>','win','left=100,top=100,height=340,width=700,resizable=1,scrollbars=1')">
                        </td>
                        <td>
                            <img title="Hapus File" style="cursor:pointer" src="<? echo $bit_app["path_url"] ?>bit_images/hapus.png" onClick="var ans;ans=confirm('Anda yakin untuk menghapus data ini [<? echo $rs->value[$i][3] ?>]  ?'); if (ans) {document.forms[0].hdAction.value=3;document.forms[0].id.value=<? echo $rs->value[$i][1] ?>;document.forms[0].submit();}">
                        </td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <table width="100%">
            <td align="center">
                <?
                $tmp = ceil(($totalRow / $bit_app["sumOfRow"]));
                if ((($_POST["slPage"] - 1) * $bit_app["sumOfPage"]) + $bit_app["sumOfPage"] <= $tmp)
                    $maxCount = (($_POST["slPage"] - 1) * $bit_app["sumOfPage"]) + $bit_app["sumOfPage"];
                else
                    $maxCount = $tmp;

                echo "page : &nbsp;&nbsp;";

                for ($i = (($_POST["slPage"] - 1) * $bit_app["sumOfPage"]) + 1; $i <= $maxCount; $i++) {
                    echo "<a href='#' onClick='document.forms[0].page.value=" . (int) $i . ";document.forms[0].submit()'>&nbsp;$i&nbsp;</a>";
                }
                ?>
                <?
                if (ceil($paging / $bit_app["sumOfPage"]) > 1) {
                ?>
                    <select name="slPage" class="inputPage" onChange="document.forms[0].cPage.value=1;document.form.submit()">
                        <?

                        for ($i = 1; $i <= ceil($paging / $bit_app["sumOfPage"]); $i++) {
                            if ($_POST["slPage"] == $i)
                                echo "<option selected value='$i'>$i</option>";
                            else
                                echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                <?
                }
                ?>
                <input type="hidden" name="page" value="<? echo $_POST["page"] ?>">
                <input type="hidden" name="cPage" value="0">
                <input type="hidden" name="hdAction" value="0">
                <input type="hidden" name="abj" value="<? echo $_POST["abj"] ?>">
                <input type="hidden" name="status">
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="idBB" value="0">

            </td>
        </table>
    </p>
    <hr class="article_layout_hr">
    <p align="right">
        <? $f->button("add", "Tambah", "button", "window.open('" . $bit_app["path_url"] . "/bit_content/content/kebijakanAdd.php','win','height=340,width=600,resizable=1,scrollbars=1')") ?>
        &nbsp;
        <? $f->button("refresh", "Refresh", "button", "window.location=window.location.href") ?>
    </p>
    <?
    for ($i = 0; $i < count($arrField); $i++) {
        echo "<input type='hidden' name='hdOrder[$i]' id='hdOrder' value='" . $_POST["hdOrder"][$i] . "'>";
    }
    ?>
    <? $f->closeForm();
    $ora->logoff();
    ?>
</body>

</html>