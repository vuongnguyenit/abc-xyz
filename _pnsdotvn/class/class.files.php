<?php
class FileSystem
{
	function FileSystem ()
  {
    global $vnT, $conf , $func;
		//load upload
		include('upload/class.upload.php');
    if(!class_exists("upload"))
		{
			die('Class Upload not found !!!') ;
		}
  }	
	
	function get_file_name($file_name) {
		ereg("(.+)\.(.+)", basename($file_name), $regs);
		return $regs[1];
	}
	
  /**
   * @function : thum  
   * @param 		: $imgfile ->  hinh nguon
   *						: $path  ->  hinh dich
   *						: $max -> kich thuoc	
   * @return		: none
   */
  function thum ($imgfile = "", $path, $max)
  {
		global $vnT, $conf , $func;
    $info = @getimagesize($imgfile);
    $mime = $info[2];
    $fext = ($mime == 1 ? 'image/gif' : ($mime == 2 ? 'image/jpeg' : ($mime == 3 ? 'image/png' : NULL)));
    switch ($fext) {
      case 'image/jpeg':
        if (! function_exists('imagecreatefromjpeg')) {
          die('No create from JPEG support');
        } else {
          $img['src'] = @imagecreatefromjpeg($imgfile);
        }
      break;
      case 'image/png':
        if (! function_exists('imagecreatefrompng')) {
          die("No create from PNG support");
        } else {
          $img['src'] = @imagecreatefrompng($imgfile);
        }
      break;
      case 'image/gif':
        if (! function_exists('imagecreatefromgif')) {
          die("No create from GIF support");
        } else {
          $img['src'] = @imagecreatefromgif($imgfile);
        }
      break;
    }
    $img['old_w'] = @imagesx($img['src']);
    $img['old_h'] = @imagesy($img['src']);
    $new_h = $img['old_h'];
    $new_w = $img['old_w'];
    if ($img['old_w'] > $max) {
      $new_w = $max;
      $new_h = ($max / $img['old_w']) * $img['old_h'];
    }
    if ($new_h > $max) {
      $new_h = $max;
      $new_w = ($new_h / $img['old_h']) * $img['old_w'];
    }
    $img['des'] = @imagecreatetruecolor($new_w, $new_h);
    $white = @imagecolorallocate($img['des'], 255, 255, 255);
    @imagefill($img['des'], 1, 1, $white);
    @imagecopyresampled($img['des'], $img['src'], 0, 0, 0, 0, $new_w, $new_h, $img['old_w'], $img['old_h']);
    //	print "path = ".$path."<br>";	
    @touch($path);
    switch ($fext) {
      case 'image/pjpeg':
      case 'image/jpeg':
      case 'image/jpg':
        @imagejpeg($img['des'], $path, 90);
      break;
      case 'image/png':
        @imagepng($img['des'], $path, 90);
      break;
      case 'image/gif':
        @imagegif($img['des'], $path, 90);
      break;
    }
    // Finally, we destroy the images in memory.
    @imagedestroy($img['des']);
  }

  /**
   * @function : Save  
   * @param 		: $imgfile ->  hinh nguon
   *						: $path  ->  hinh dich
   *						: $w -> kich thuoc	
   * @return		: none
   */
  function Save ($imgfile = "", $path, $w)
  {
		global $vnT, $conf , $func;
    $gd_version = 2;
    $info = getimagesize($imgfile);
    $mime = $info[2];
    $fext = ($mime == 1 ? 'image/gif' : ($mime == 2 ? 'image/jpeg' : ($mime == 3 ? 'image/png' : NULL)));
    switch ($fext) {
      case 'image/pjpeg':
      case 'image/jpeg':
      case 'image/jpg':
        if (! function_exists('imagecreatefromjpeg')) {
          die('No create from JPEG support');
        } else {
          $img['src'] = @imagecreatefromjpeg($imgfile);
        }
      break;
      case 'image/png':
        if (! function_exists('imagecreatefrompng')) {
          die("No create from PNG support");
        } else {
          $img['src'] = @imagecreatefrompng($imgfile);
        }
      break;
      case 'image/gif':
        if (! function_exists('imagecreatefromgif')) {
          die("No create from GIF support");
        } else {
          $img['src'] = @imagecreatefromgif($imgfile);
        }
      break;
      default:
        die("Định dang file không hợp lệ");
    }
    $img['old_w'] = imagesx($img['src']);
    $img['olh_h'] = imagesy($img['src']);
    if ($w != 0) {
      if ($img['old_w'] > $w)
        $h = ($w / $img['old_w']) * $img['olh_h'];
      else {
        $w = $img['old_w'];
        $h = $img['olh_h'];
      }
      if ($h > $w * 2) {
        $w = ($w * 2 / $h) * $w;
        $h = $w * 2;
      }
    } else {
      $w = $img['old_w'];
      $h = $img['olh_h'];
    }
    if ($w < 96) {
      $space = (96 - $w) / 2;
      $w = 96;
    } else {
      $space = 0;
    }
    if ($gd_version == 2) {
      $img['des'] = imagecreatetruecolor($w, $h);
      $balck = imagecolorallocate($img['des'], 255, 255, 255);
      imagefill($img['des'], 1, 1, $balck);
      imagecopyresampled($img['des'], $img['src'], $space, 0, 0, 0, $w - ($space * 2), $h, $img['old_w'], $img['olh_h']);
    }
    if ($gd_version == 1) {
      $img['des'] = imagecreatetruecolor($w, $h);
      $white = imagecolorallocate($img['des'], 255, 255, 255);
      imagefill($img['des'], 1, 1, $white);
      imagecopyresized($img['des'], $img['src'], $space, 0, 0, 0, $w - ($space * 2), $h, $img['old_w'], $img['olh_h']);
    }
    touch($path);
    switch ($fext) {
      case 'image/pjpeg':
      case 'image/jpeg':
      case 'image/jpg':
        imagejpeg($img['des'], $path, 90);
      break;
      case 'image/png':
        imagepng($img['des'], $path, 90);
      break;
      case 'image/gif':
        imagegif($img['des'], $path, 90);
      break;
      default:
        die("Định dang file không hợp lệ");
    }
    // Finally, we destroy the images in memory.
    imagedestroy($img['des']);
  }

  // End 
	
	/**
   * @function : Upload_Pic  
   * @param 		: $data -> 1 mang  
   * @return		: 1 mang ket qua 
   */
  function Upload_Pic ($data)
  {
		global $vnT, $conf , $func;
    // Upload
    $path = $data['path'] . $data['dir'];
    $max_size = 10000000;
    $err = "";
    $image = $data['file'];
    $image['name'] = str_replace("%20", "_", $image['name']);
    $image['name'] = str_replace(" ", "_", $image['name']);
    $image['name'] = str_replace("&amp;", "_", $image['name']);
    $image['name'] = str_replace("&", "_", $image['name']);
    $image['name'] = str_replace(";", "_", $image['name']);
    $type = strtolower(substr($image['name'], strrpos($image['name'], ".") + 1));
    $src_name = substr($image['name'], 0, strrpos($image['name'], "."));
    if ($data['resize'])
      $w = $data['w'];
    if ($image['size'] > 0) {
      if ($image['size'] > $max_size)
        $err .= "File hình quá lớn :(";
        //			echo 		"type = ".$image['type']."<br>"; 
      if ($data['type'] == "hinh") {
        if (($image['type'] == "image/gif") || ($image['type'] == "image/pjpeg") || ($image['type'] == "image/jpeg") || ($image['type'] == "image/jpg") || ($image['type'] == "image/png") || ($image['type'] == "application/x-shockwave-flash")) {
          if ($data['pic_name']) {
            $des_name = $data['pic_name'];
            $fname = $path . "/" . $des_name . "." . $type;
          } else {
            $des_name = $src_name;
            $fname = $path . "/" . $image['name'];
          }
          if ($data['overwrite'] != 1) {
            if (file_exists($fname)) {
              $des_name = $des_name . "_" . time();
            }
          }
        } else
          $err .= "- Định dang file hình không hợp lệ !";
      } else {
        $des_name = $src_name;
        $fname = $path . "/" . $image['name'];
      }
      if (empty($err)) {
        $image_name = $des_name . "." . $type;
        $link_file = $path . "/" . $image_name;
        if (($data['type'] == "hinh") && ($image['type'] != "application/x-shockwave-flash")) {
          if ($data['thum'] == 1) {
						if ($data['change_name_thum']) {
							$link_file1 = $path . "/thumbs/" . $image_name;
						}else{
							$link_file1 = $path . "/thumbs/" . $data['w_thum'] . "_" . $image_name;
						}
            
            $this->thum($image['tmp_name'], $link_file1, $data['w_thum']);
          }
          $this->Save($image['tmp_name'], $link_file, $w);
        } else {
          if ($data['thum'] == 1) {
						if ($data['change_name_thum']) {
							$link_file1 = $path . "/thumbs/" . $image_name;
						}else{
							$link_file1 = $path . "/thumbs/" . $data['w_thum'] . "_" . $image_name;
						}
            $res1 = copy($image['tmp_name'], $link_file1);
          }
          $res = copy($image['tmp_name'], $link_file);
        }
        $re['link'] = $image_name;
        $re['type'] = $type;
      }
    }
    // End upload
    $re['err'] = $err;
    return $re;
  }


  /**
   * Ham  Upload 
   * @param	$data : 1 mang cac thong tin ve upload
   * @ouput	$re  : 1mang ket qua ($link, $err)
   *
   */
  function Upload ($data)
  {
    global $vnT, $conf;
    // Upload
    include ('upload/class.upload.php');
    $path = $data['path'] . $data['dir'];
    // ---------- IMAGE UPLOAD ----------
    $handle = new upload($data['file']);
    if ($data['type'] == "hinh") {
      if ($handle->uploaded) {
        $handle->allowed = array(
          "image/*" , 
          "application/x-shockwave-flash");
        if ($handle->file_src_name_ext == "jpg" || $handle->file_src_name_ext == "gif" || $handle->file_src_name_ext == "png") {
          switch ($handle->file_src_name_ext) {
            case 'jpg':
              $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
            break;
            case 'gif':
              $image_src = @imagecreatefromgif($handle->file_src_pathname);
            break;
            case 'png':
              $image_src = @imagecreatefrompng($handle->file_src_pathname);
            break;
            default:
              $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
            break;
          }
          $image_src_x = @imagesx($image_src);
          $image_src_y = @imagesy($image_src);
          if ($image_src_x > $data['w']) {
            $handle->image_resize = true;
          } else {
            $handle->image_resize = false;
          }
        }
        $handle->image_ratio_y = true;
        $handle->image_x = $data['w'];
        if ($data['change_name'] == 1) {
          $handle->file_dst_name = $data['pic_name'] . "." . $handle->file_src_name_ext;
          $handle->file_src_name_body = $data['pic_name'];
        }
        $handle->process($path);
        if ($handle->processed) {
          $re['link'] = $handle->file_dst_name;
          $re['size'] = $handle->file_src_size;
          $re['type'] = $handle->file_dst_name_ext;
        } else {
          $err = $vnT->lang['file_invalid'];
        }
        //thumb
        if ($data['thum'] == 1 && $re['link']) {
          $path_thumb = $path . "/thumbs";
          $handle->image_resize = true;
          
					if($data['resize_crop'] == 1 )
					{
						$handle->image_ratio_crop = true;
						$handle->image_y = $data['w_thum'];
						$handle->image_x = $data['w_thum'];
					}else{
						if ($image_src_y > $image_src_x) {
							$handle->image_ratio_x = true;
							$handle->image_y = $data['w_thum'];
						} else {
							$handle->image_ratio_y = true;
							$handle->image_x = $data['w_thum'];
						}
					}
					
          if ($data['change_name_thum']) {
            $handle->file_dst_name = $data['w_thum'] . "_" . $handle->file_src_name_ext;
            $handle->file_src_name_body = $data['w_thum'] . "_" . $handle->file_src_name_body;
          }
          $handle->process($path_thumb);
        }
      } else {
        $err = '  Error: ' . $handle->error . '';
      }
      $err = $handle->error;
    } else {
      $handle->process($path);
      if ($handle->processed) {
        $re['link'] = $handle->file_dst_name;
        $re['size'] = $handle->file_src_size;
        $re['type'] = $handle->file_dst_name_ext;
      }
    }
    //	echo($handle->log);
    //echo  "err = ".	$err ;
    $re['err'] = $err;
    return $re;
  }

  /**
   * @function : UploadURL  
   * @param 		: $data -> 1 mang  
   * @return		: 1 mang ket qua 
   */
  function UploadURL ($data)
  {
    global $vnT;
    // Upload
    //include ('../includes/class.upload.php');
    $path = $data['path'] . $data['dir'];
    $arr_allow = array(
      "jpg" , 
			"png" , 
      "jpeg" , 
      "gif");
    $url = chop($data['url']);
    $fext = strtolower(substr($url, strrpos($url, ".") + 1));
    $lastx = strrpos($url, "/");
    if (in_array($fext, $arr_allow)) {
      $fname = $path . "/" . substr($url, $lastx + 1);
      $fname = str_replace("%20", "_", $fname);
      $fname = str_replace(" ", "_", $fname);
      $fname = str_replace("&amp;", "_", $fname);
      $fname = str_replace("&", "_", $fname);
      $fname = str_replace(";", "_", $fname);
      $file_name = time() . ".{$fext}";
      $fname = $path . "/" . $file_name;
      $file = fopen($fname, "w");
      if ($f = fopen($url, "r")) {
        while (! @feof($f)) {
          @fwrite($file, fread($f, 1024));
        }
        @fclose($f);
        @fclose($file);
        $path_desc = $path . "/" . $file_name;
        // ---------- IMAGE UPLOAD ----------
        $handle = new upload($path_desc);
        if ($data['type'] == "hinh") {
          if ($handle->uploaded) {
            $handle->allowed = array(
              "image/gif" , 
              "image/jpeg" , 
              "image/pjpeg" , 
              "image/png" , 
              "application/x-shockwave-flash");
            if ($handle->file_src_name_ext == "jpg" || $handle->file_src_name_ext == "gif" || $handle->file_src_name_ext == "png") {
              switch ($handle->file_src_name_ext) {
                case 'jpg':
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
                case 'gif':
                  $image_src = @imagecreatefromgif($handle->file_src_pathname);
                break;
                case 'png':
                  $image_src = @imagecreatefrompng($handle->file_src_pathname);
                break;
                default:
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
              }
              $image_src_x = @imagesx($image_src);
              $image_src_y = @imagesy($image_src);
              if ($image_src_x > $data['w']) {
                $handle->image_resize = true;
              } else {
                $handle->image_resize = false;
              }
            }
            $handle->image_ratio_y = true;
            $handle->image_x = $data['w'];
            $pic_name = $this->get_file_name($url);
            $handle->file_dst_name = $pic_name . $handle->file_src_name_ext;
            $handle->file_src_name_body = $pic_name;
            $handle->process($path);
            if ($handle->processed) {
              $re['link'] = $handle->file_dst_name;
              $re['size'] = $handle->file_src_size;
              $re['type'] = $handle->file_dst_name_ext;
            } else {
              $err = $vnT->lang['file_invalid'];
            }
            //thumb
            if ($data['thum'] == 1 && $re['link']) {
              $path_thumb = $path . "/thumbs";
              $handle->image_resize = true;
             
						  if($data['resize_crop'] == 1 )
							{
								$handle->image_ratio_crop = true;
								$handle->image_y = $data['w_thum'];
								$handle->image_x = $data['w_thum'];
							}else{
								if ($image_src_y > $image_src_x) {
									$handle->image_ratio_x = true;
									$handle->image_y = $data['w_thum'];
								} else {
									$handle->image_ratio_y = true;
									$handle->image_x = $data['w_thum'];
								}
							}
							
              if ($data['change_name_thum']) {
                $handle->file_dst_name = $data['w_thum'] . "_" . $handle->file_src_name_ext;
                $handle->file_src_name_body = $data['w_thum'] . "_" . $handle->file_src_name_body;
              }
              $handle->process($path_thumb);
            }
          } else {
            $err = '  Error: ' . $handle->error . '';
          }
          $err = $handle->error;
        } else {
          $handle->process($path);
          if ($handle->processed) {
            $re['link'] = $handle->file_dst_name;
            $re['size'] = $handle->file_src_size;
            $re['type'] = $handle->file_dst_name_ext;
          }
        }
        //echo($handle->log);
        $re['err'] = $err;
      } else {
        $re['err'] = $vnT->lang['not_read_file'];
      }
    } else {
      $re['err'] = $vnT->lang['file_invalid'];
    }
    return $re;
  }

  /**
   * Ham  Muti_Upload 
   * @param	$data : 1 mang cac thong tin ve upload
   * @ouput	$re  : 1mang ket qua ($link, $err)
   *
   */
  function Muti_Upload ($data)
  {
    // Upload
    //include ('../includes/class.upload.php');
    $path = $data['path'] . $data['dir'];
    $err = "";
    $files = array();
    foreach ($data['file'] as $k => $l) {
      foreach ($l as $i => $v) {
        if (! array_key_exists($i, $files))
          $files[$i] = array();
        $files[$i][$k] = $v;
      }
    }
    $n = 0;
    foreach ($files as $file) {
      if ($file['name'] != "") {
        // we instanciate the class for each element of $file
        $handle = new upload($file);
        if ($data['type'] == "hinh") {
          if ($handle->uploaded) {
            $handle->allowed = array(
              "image/gif" , 
              "image/jpeg" , 
              "image/pjpeg" , 
              "image/png" , 
              "application/x-shockwave-flash");
            if ($handle->file_src_name_ext == "jpg" || $handle->file_src_name_ext == "gif" || $handle->file_src_name_ext == "png") {
              switch ($handle->file_src_name_ext) {
                case 'jpg':
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
                case 'gif':
                  $image_src = @imagecreatefromgif($handle->file_src_pathname);
                break;
                case 'png':
                  $image_src = @imagecreatefrompng($handle->file_src_pathname);
                break;
                default:
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
              }
              $image_src_x = @imagesx($image_src);
              $image_src_y = @imagesy($image_src);
              if ($image_src_x > $data['w']) {
                $handle->image_resize = true;
              } else {
                $handle->image_resize = false;
              }
            }
            $handle->image_ratio_y = true;
            $handle->image_x = $data['w'];
            $handle->process($path);
            if ($handle->processed) {
              $re['link'][$n] = $handle->file_dst_name;
              $re['size'][$n] = $handle->file_src_size;
              $re['type'][$n] = $handle->file_dst_name_ext;
            } else {
              $err = $vnT->lang['file_invalid'];
            }
            //thumb
            if ($data['thum'] == 1 && $re['link'][$n]) {
              $path_thumb = $path . "/thumbs";
              $handle->image_resize = true;
              if($data['resize_crop'] == 1 )
							{
								$handle->image_ratio_crop = true;
								$handle->image_y = $data['w_thum'];
								$handle->image_x = $data['w_thum'];
							}else{
								if ($image_src_y > $image_src_x) {
									$handle->image_ratio_x = true;
									$handle->image_y = $data['w_thum'];
								} else {
									$handle->image_ratio_y = true;
									$handle->image_x = $data['w_thum'];
								}
							}
              if ($data['change_name_thum']) {
                $handle->file_dst_name = $data['w_thum'] . "_" . $handle->file_src_name_ext;
                $handle->file_src_name_body = $data['w_thum'] . "_" . $handle->file_src_name_body;
              }
              $handle->process($path_thumb);
            }
          } else {
            $err .= '  Error: ' . $handle->error . '';
          }
          $err .= $handle->error;
        } else {
          $handle->process($path);
          if ($handle->processed) {
            $re['link'][$n] = $handle->file_dst_name;
            $re['size'][$n] = $handle->file_src_size;
            $re['type'][$n] = $handle->file_dst_name_ext;
          }
        }
      } //end if empty name
      $n ++;
    }
    $re['err'] = $err;
    //echo($handle->log);
    return $re;
  }

  /**
   * Ham  Muti_UploadURL 
   * @param	$data : 1 mang cac thong tin ve upload
   * @ouput	$re  : 1mang ket qua ($link, $err)
   *
   */
  function Muti_UploadURL ($data)
  {
    // Upload
    //include ('../includes/class.upload.php');
    $path = $data['path'] . $data['dir'];
    $err = "";
    $n = 0;
    foreach ($data['file'] as $key => $file) {
      if ($file != "") {
        // we instanciate the class for each element of $file
        $handle = new upload($file);
        if ($data['type'] == "hinh") {
          if ($handle->uploaded) {
            $handle->allowed = array(
              "image/gif" , 
              "image/jpeg" , 
              "image/pjpeg" , 
              "image/png" , 
              "application/x-shockwave-flash");
            if ($handle->file_src_name_ext == "jpg" || $handle->file_src_name_ext == "gif" || $handle->file_src_name_ext == "png") {
              switch ($handle->file_src_name_ext) {
                case 'jpg':
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
                case 'gif':
                  $image_src = @imagecreatefromgif($handle->file_src_pathname);
                break;
                case 'png':
                  $image_src = @imagecreatefrompng($handle->file_src_pathname);
                break;
                default:
                  $image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                break;
              }
              $image_src_x = @imagesx($image_src);
              $image_src_y = @imagesy($image_src);
              if ($image_src_x > $data['w']) {
                $handle->image_resize = true;
              } else {
                $handle->image_resize = false;
              }
            }
            $handle->image_ratio_y = true;
            $handle->image_x = $data['w'];
            $pic_name = $this->get_file_name($file);
            $handle->file_dst_name = $pic_name . $handle->file_src_name_ext;
            $handle->file_src_name_body = $pic_name;
            $handle->process($path);
            if ($handle->processed) {
              $re['link'][$key] = $handle->file_dst_name;
              $re['size'][$key] = $handle->file_src_size;
              $re['type'][$key] = $handle->file_dst_name_ext;
            } else {
              $err = $vnT->lang['file_invalid'];
            }
            //thumb
            if ($data['thum'] == 1 && $re['link'][$key]) {
              $path_thumb = $path . "/thumbs";
              $handle->image_resize = true;
              if($data['resize_crop'] == 1 )
							{
								$handle->image_ratio_crop = true;
								$handle->image_y = $data['w_thum'];
								$handle->image_x = $data['w_thum'];
							}else{
								if ($image_src_y > $image_src_x) {
									$handle->image_ratio_x = true;
									$handle->image_y = $data['w_thum'];
								} else {
									$handle->image_ratio_y = true;
									$handle->image_x = $data['w_thum'];
								}
							}
              if ($data['change_name_thum']) {
                $handle->file_dst_name = $data['w_thum'] . "_" . $handle->file_src_name_ext;
                $handle->file_src_name_body = $data['w_thum'] . "_" . $handle->file_src_name_body;
              }
              $handle->process($path_thumb);
            }
          } else {
            $err .= '  Error: ' . $handle->error . '';
          }
          $err .= $handle->error;
        } else {
          $handle->process($path);
          if ($handle->processed) {
            $re['link'][$key] = $handle->file_dst_name;
            $re['size'][$key] = $handle->file_src_size;
            $re['type'][$key] = $handle->file_dst_name_ext;
          }
        }
      } //end if empty name
      $n ++;
    }
    $re['err'] = $err;
    //echo($handle->log);
    return $re;
  }
	
}
?>