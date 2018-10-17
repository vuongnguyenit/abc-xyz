<?php include('index_table.php') ?>
<div align="center">
  <div id="indexCenter" align="center">
    <table cellpadding="0" cellspacing="0" id="indexTable">
      <?php echo $dbf->returnTitleMenu('Welcome to Administrator System') ?>
      <tr>
        <td align="center" valign="top"><div style="padding-top: 10px; margin-left: 15px;!margin-left: 5px; float: left; margin-right: 10px;"><?php $dbf->showIndex() ?></div></td>
      </tr>
      <tr>
        <td class="boxRed" colspan="5"><div class="copyright">&copy; 2011 - <?php echo date('Y') ?> Thiết kế bởi <a class="copyright" target="_blank" href="http://pns.vn" title="www.pns.vn | Phương Nam Solution">Phuong Nam Solution</a> - <a class="copyright" target="_blank" href="http://dynweb.vn">DynWeb.vn</a></div></td>
      </tr>
    </table>
  </div>
</div>
<?php ob_end_flush() ?>
