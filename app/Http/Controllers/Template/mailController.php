<?php

namespace App\Http\Controllers\Template;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class mailController extends Controller
{


  public static function signUPotp(array $request)
  {
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket Purchase OTP Registered Template</title>

    <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=cOHVXauret47m7vfvvQhf02-NcodCzBsAzoj0F1AHQgPkD9Rj1YHZaoHPpoqjmlFYOj6jJ3T7iZ4ouw5wIdI1iWh-rYN2IwIddwzX0pKgHcRyYHURyLdo5E9133N8cCX" charset="UTF-8"></script><style type="text/css">



      @import url("https://fonts.googleapis.com/css2?family=Barlow+Condensed&display=swap");

      body {

        margin: 0;

      }

      .wrapper {



        background:#CCC;



        }

      .main {



        background:#FFF;

        max-width:600px;



        }



      table {

        border-spacing: 0;

      }



      img {

        border: 0;

      }

      .column-one {



        text-align:center;

        margin:0 auto;

        }

      .column-one .column {



        width:100%;

          margin:0 auto;



        }







    </style>

    </head>

    <body>



      <center class="wrapper">



        <table class="main" width="100%">

            

            <tr><td class="column-one" style=" background: #29377d; height:54px;">





            </td></tr>



            <tr><td class="column-one" style="background: radial-gradient(circle,#fcef48 0%,#fdd206 100%); height:15px;">





            </td></tr>




            <tr><td class="column-one" >

            <table class="column"> <tr><td valign="top" style="padding: 16px 0 37px 0;">

            <center>

              <img src="' . env('Base_URL') . 'assets/images/mailtemplate/logo1.png"  style="border: 0px;"  >



            </center>



              </td></tr></table>



            </td></tr>

            <!-- LOGO  -->

                    <tr>

                      <td class="column-one" >

              <table align="center" class="column" style="
        background: url(' . env('Base_URL') . 'assets/images/mailtemplate/new_man.png)no-repeat;
        height: 300px;background-position: center;    margin: -26px 0 0 0 !important;
        "> <tbody><tr><td colspan="3" valign="top" style="padding:10px 0px 0px 10px;">


    <h3 class="demoname" style="color: #be1e2d;  font-family: Arial Narrow;font-style: italic;font-size: 32px; margin: 0px 0px 0px 24px; text-align: center;">Hi, ' . $request['name'] . '


                        </h3>


                    </td></tr><tr>
                      <td>


                     </td>


    </tr>


              </tbody></table>


            </td></tr>



    <tr>

                      <td class="column-one" >

            <table align="center" class="column"> <tr>

              <td valign="top" >

                <table style="margin: auto; color: #000000;  font-size: medium; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

          <tbody>



                    <tr>

              <td style="color: #666666; background: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-origin: initial; background-clip: initial; font-size: 15px; line-height: 25px;" align="center" bgcolor="#e4dcf1">

                <p style="color: #29377d;  font-size:163%; text-align: center;font-style: italic;font-family: Arial Narrow;line-height:30px;">Please use the below OTP to complete the<br>registration with Little Draw</p>

              </td>

                    </tr>

                    <tr>
                <td style=" border-radius: 4px 4px 0px 0px; color: #111111; font-size: 24px; line-height: 24px;padding: 10px;" align="center" valign="top" bgcolor="#ffffff">
                  <h3 style="color: #ffffff; font-size: 36px; margin: 0px; font-style: italic; font-family: Arial Narrow;padding: 9px; background: #be1e2d; width: 119px; line-height: 1; border-radius: 11px; border: 3px dashed #ffffff;">' . $request['randotp'] . '</h3>
                </td>
              </tr>

          </tbody>

        </table>

        <br>





        <table style="margin: auto; color: #000000;  font-size: medium; background-color: #fbfbfb; border-collapse: collapse;" border="0" cellspacing="0" cellpadding="0">

          <tbody>

            <tr>

              <td class="gmail-line" style="box-sizing: border-box; width: 8px;">

                <img  style="width:489px !important;"src="' .  env('Base_URL') . 'assets/images/mailtemplate/center_img2.png">

              </td>

            </tr>

          </tbody>

        </table>
        <br>


        <p style="color: #29377d !important;  font-size: 22px !important; margin: 0px !important; text-align: center !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Need help?
    +971 433 98880<br>Support@littledraw.ae

        </p>

    <br>

    <p style="color: #29377d;  font-size: 152%; margin: 0px; background-color: #fbfbfb; text-align: center;font-style: italic;font-family: Arial Narrow;margin: 8px 0px 0px 0px;">Little Draw</p>


    <p style="color: #29377d;  font-size: 152%; margin: 0px; background-color: #fbfbfb; text-align: center;font-style: italic;font-family: Arial Narrow;margin: 8px 0px 0px 0px;">Office 202 H, lbn Battuta Gate Offices,

      <br>P.O.Box:451394, Dubai, UAE.

    </p>
        <p style="color: #29377d !important;font-size: 15px !important;margin: 0px !important;text-align: center !important;font-weight: 500 !important;font-style: italic !important;font-family: Arial Narrow !important;margin: 8px 0px 0px 0px !important;">Note: This is a system auto generated email. Please do not reply to this mail.<br>
        
        For Clarification
        
         
        
               <br>
        
        Call 04 33 98880 Whatsapp +971 56 199 1271
        
        <br>
        
        or email support@littledraw.ae</p>
              </td></tr></table>



            </td></tr>


        </table>



      </center>



    </body>

    </html>';

    return $html;
  }
}
