<table width="100%" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
    <tbody>
        <tr>
          <td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
              <tbody>
              <tr>
                  <td width="100%">
                  <table bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                      <tbody>
                      <tr>
                          <td width="100%" height="20"></td>
                        </tr>
                      <tr>
                          <td width="100%"><table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                              <tbody>
                              <tr>
                                  <td><table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                      <tbody>
                                      <tr>
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top; padding:10px 0;"><strong> Dear {{ $user->firstname }} {{ $user->lastname }},</strong></td>
                                        </tr>
                                      
            
                                     {{-- <tr>
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:16px; line-height:22px; vertical-align: top; padding:10px 0;"><span style="color:#7a2a90">Thanks</span> for signing up! </td>
                                        </tr>
                                      <tr>--}}
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top;">Please use the otp below to verify your email address and complete your tranasction process.</td>
                                        </tr>
                                        
                                       
                                         <tr>
                                                    <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top;">

                                                        {{--<a href="{{ config('variable.FRONTEND_URL') }}/verification/<?php echo $user->verify_token;?>" style="color: #90cbf5;">--}}
                                                            <h1 style="color: #90cbf5;">{{$user->transaction_otp}}</h1>
                                                        {{--</a>--}}

                                                    </td>
                                                </tr>
                                        
                                    </tbody>
                                    </table></td>
                                </tr>
                              {{--<tr>
                                  <td><table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                      <tbody>
                                      <tr>
                                          <td width="100%" height="15"></td>
                                        </tr>
                                      {{--<tr>
                                          <td width="100%" height="10" style="border-top:1px solid #eee"></td>
                                        </tr>
                                         <tr>
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top;">If above link does not works then copy paste below url in your browser</td>
                                        </tr>
                                        
                                      <tr>
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top; color:#7a2a90; ">
                                              <a style="color:#7a2a90" href="{{ config('variable.FRONTEND_URL') }}/verification/<?php echo $user->verify_token;?>">{{ config('variable.FRONTEND_URL') }}/verification/<?php echo $user->verify_token;?></a>
                                          </td>
                                        </tr>
                                      <tr>
                                          <td width="100%" height="10" style="border-bottom:1px solid #eee"></td>
                                        </tr>
                                      <tr>
                                          <td width="100%" height="10"></td>
                                        </tr>
                                    </tbody>
                                    </table></td>
                                </tr>
                             
                              <tr>--}}
                                  <td><table width="560" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                      <tbody>
                                      <tr>
                                          <td style="font-family: Open Sans,open-sans,sans-serif; font-size:14px; line-height:22px; vertical-align: top; padding:10px 0; "> Kind regards, </td>
                                        </tr>
                                      <tr>
                                          <td> Blockchain Team </td>
                                        </tr>
                                    
                                    </tbody>
                                    </table></td>
                                </tr>
                            
                              {{--<tr>
                                  <td style="border-bottom:2px  #ccc; height:5px; padding:10px;"></td>
                                </tr>--}}
                            </tbody>
                            </table></td>
                        </tr>
                    </tbody>
                    </table>

                 </td>
               </tr>
          </td>
        </tr>
    </tbody>
</table>


<table  width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
       <td>
           <tr>
            <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#fe6a44" align="center">
                <tr>
                   <td height="20px"></td>
                </tr>
            </table>


           </tr>
       </td>
    </tr>
</table>