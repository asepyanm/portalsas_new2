<?php require_once('header.php');
?>


<link rel="stylesheet" type="text/css" href="<? echo $bit_app["path_url"] ?>bit_css/standart.css">
<!--================Blog Area =================-->
<section class="blog_area single-post-area section_padding bg_page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 posts-list">
                <div class="single-post">

                    <div class="blog_details">

                        <?php
                        $f = new clsForm;
                        $f->openForm("frmMain", "frmMain", "", "POST", "multipart/form-data");
                        ?>
                        <table cellpadding="1" cellspacing="1" border="0" width="100%">
                            <tr>
                                <td colspan="5">
                                    <? echo setTitle("Peraturan Perundang Undangan"); ?>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="1" cellspacing="0">
                            <tr>
                                <td align="right">
                                    Cari : <input type="text" name="tSearch" size="30" value="<? if ($_POST["tSearch"]) echo $_POST["tSearch"];
                                                                                                else echo "Search" ?>" onMouseOver="if (this.value=='Search') this.value=''" onMouseOut="if (this.value=='') this.value='Search'">
                                    <input type="submit" class="genric-btn primary circle" name="sGo" onClick="document.forms[0].page.value=1" value="Go">
                                    (Pencarian terhadap Judul dan Nama File)
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="1" border="0" width="100%" class="table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="listTableHead">NO</th>
                                    <th rowspan="2" class="listTableHead">JUDUL</th>
                                    <th rowspan="2" class="listTableHead">NAMA FILE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $ora = new clsMysql;
                                $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);


                                if ($_POST["sGo"] && $_POST["sGo"] != "Search") {
                                    $where = " WHERE (
							judul like '%" . $_POST["tSearch"] . "%'
						    or
							nama_file like '%" . $_POST["tSearch"] . "%'
						)";
                                } else {
                                    $where = '';
                                }

                                $qry = "select count(1) from m_kebijakan $where";
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
                                $qry = "SELECT * FROM m_kebijakan $where $orderBy limit " . ($rownum1 - 1) . "," . $bit_app["sumOfRow"] . "";
                                $rs = $ora->sql_fetch($qry, $bit_app["db"]);

                                for ($i = 1; $i <= $rs->jumrec; $i++) {
                                    if ($i % 2 == 0)
                                        $cl = "listTableRow";
                                    else
                                        $cl = "listTableRowS";
                                ?>
                                    <tr class="<? echo $cl ?>">
                                        <td align="center" width="2%" valign="top"><? echo ((($page - 1) * $bit_app["sumOfRow"]) + $i) ?></td>
                                        <td align="center" width="20%" valign="top"><? echo $rs->value[$i][2] ?></td>
                                        <td align="center" width="20%" valign="top"><a href="<?= $bit_app["path_url"] . 'bit_mod/showdoc.php?target=' . $rs->value[$i][3]; ?>" target="_blank"><b><?= $rs->value[$i][3]; ?></b></a></td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                        <table width="100%" class="table">
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
                                <input type="hidden" name="id" value="0">
                                <input type="hidden" name="status">
                            </td>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
</section>




<?php require_once('footer.php'); ?>