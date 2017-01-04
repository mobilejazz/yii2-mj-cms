<?php

use mobilejazz\yii2\cms\common\models\User;
use yii\web\View;

/**
 * @var View $this
 * @var User $model
 */
// ============================
// USER REGISTRATION COMPLETED (NOT VALIDATED) MAIL.
// ============================
?>
<div class="wrapper" style="width:100%;max-width:600px;margin-left:auto;margin-right:auto;">
    <!-- Main table -->
    <table id="container" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <!-- Header and logo -->
        <tr>
            <td class="padding background-white" width="100%"
                style="background-color:#FFFFFF;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;">
               
            </td>
        </tr>

        <!-- Start content and components -->

        <!-- Blocks -->
        <tr>
            <td width="100%">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td class="column-50 background-navy-blue" width="50%" align="left" valign="top" style="background-color:#001E46;width:50%;">
                            <div class="padding" style="padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;">
                                <h1 class="text-white text-regular no-margin"
                                    style="line-height:0.9;text-transform:uppercase;font-size:36px;font-weight:400;color:#ffffff;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                                    <?= \Yii::t('app', 'Password'); ?><br/>
                                    <span class="text-light-orange text-bold" style="font-weight:900;color:#F7A800;"><?= \Yii::t('app',
                                            'Recovery'); ?></span>
                                </h1>
                            </div>
                        </td>

                        <td class="column-50 background-blue" width="50%" align="left" valign="top"
                            style="background-color:#004B87;width:50%;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td class="background-white padding" width="100%"
                style="background-color:#FFFFFF;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;">
                <h2 style="line-height:0.9;margin-top:0;margin-bottom:0.5em;color:#001E46;text-transform:uppercase;font-weight:bold;font-size:30px;"><?= \Yii::t('app',
                        'Hello {user}', [ 'user' => $model->name, ]); ?></h2>

                <p style="margin-top:0;margin-bottom:20px;"><?= \Yii::t('app',
                        'You have requested a password reset, please click on the button bellow to update your password.'); ?></p>
                <table class="button" align="left" width="250" border="0" cellpadding="0" cellspacing="0"
                       style="margin-bottom:20px;margin-right:5px;">
                    <tr>
                        <td style="border-radius:1px;padding-top:10px;padding-bottom:10px;padding-right:20px;padding-left:20px;text-transform:uppercase;font-weight:bold;text-align:center;background-color:#0085CA;">
                            <a href="<?= $url ?>" style="text-decoration:none;color:#ffffff;"><?= \Yii::t('app', 'Reset Password Here'); ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- End content and components -->

        <!-- Footer -->
        <tr>
            <td class="padding background-medium-blue" width="100%"
                style="background-color:#00A9E0;padding-top:30px;padding-bottom:30px;padding-right:30px;padding-left:30px;">
                <p class="text-small text-white no-margin"
                   style="font-size:12px;color:#ffffff;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                    <?= \Yii::t('app', 'This email was sent by MobileJazz CMS'); ?>
                </p>
            </td>
        </tr>
    </table>
</div>