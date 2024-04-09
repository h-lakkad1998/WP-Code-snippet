<?php ?>
<div class="vote-form">
    <form id="VOTING_form">
        <div class="user-list">
            <div class="user-box">
                <div class="personimage">
                    <img height="200" width="200" src="<?php echo ZEST_URL . 'src/assets/images/user-img.jpg'; ?>" alt="usr-img">
                </div>
                <div class="person-details" >
                    <p>John Wick </p>
                    <p>
                        <?php _e('Vote candidate 1', 'multi-task'); ?>
                        <input type="radio" value="yes" name="op-id-user-1" checked id="op-id-user-1-yes">
                        <label for="op-id-user-1-yes"> <?php _e('Yes', 'multi-task'); ?></label>
                        <input type="radio" value="no" name="op-id-user-1" id="op-id-user-1-no">
                        <label for="op-id-user-1-no"> <?php _e('No', 'multi-task'); ?></label>
                    </p>
                </div>
            </div>
            <div class="user-box">
                <div class="personimage">
                    <img height="200" width="200" src="<?php echo  ZEST_URL . 'src/assets/images/user-img.jpg'; ?>" alt="usr-img">
                </div>
                <div class="person-details" >
                    <p>Adheera</p>
                    <p>
                        <?php _e('Vote candidate 2', 'multi-task'); ?>
                        <input value="yes" type="radio" name="op-id-user-2" checked id="op-id-user-2-yes">
                        <label for="op-id-user-2-yes"> <?php _e('Yes', 'multi-task'); ?></label>
                        <input value="no" type="radio" name="op-id-user-2"  id="op-id-user-2-no">
                        <label for="op-id-user-2-no"> <?php _e('No', 'multi-task'); ?></label>
                    </p>
                </div>
            </div>
            <!-- only one from both starts -->
            <div class="seprator">
                <p><b><?php _e('From the following users you can only vote one!', 'multi-task'); ?></b></p>
            </div>
            <div class="user-box">
                <div class="personimage">
                    <img height="200" width="200" src="<?php echo ZEST_URL . 'src/assets/images/user-img.jpg'; ?>" alt="usr-img">
                </div>
                <div class="person-details" >
                    <p>Klaas</p>
                    <p>
                        <input type="radio" value="user-3" name="only-one-user" checked id="only-id-user-3">
                        <label for="only-id-user-3"> <?php _e('Vote candidate 3', 'multi-task'); ?></label>
                    </p>
                </div>
            </div>
            <div class="user-box">
                <div class="personimage">
                    <img height="200" width="200" src="<?php echo ZEST_URL . 'src/assets/images/user-img.jpg'; ?>" alt="usr-img">
                </div>
                <div class="person-details" >
                    <p>Aleksa</p>
                    <p>
                        <input type="radio" value="user-4" name="only-one-user" id="only-id-user-2">
                        <label for="only-id-user-2"> <?php _e('Vote candidate 4', 'multi-task'); ?></label>
                    </p>
                </div>
            </div>
            <!-- only one from both ends -->
        </div>
        <div class="seprator">
                <p><b><?php _e('Please fill following form to submit the vote:', 'multi-task'); ?></b></p>
            </div>
        <div class="addtional-details">
            <div class="form-field full-name">
                <label for="person-fullname"><?php _e('Full name:', 'multi-task'); ?></label>
                <input id="person-fullname" required id="person-fullname" type="text" name="full-name" placeholder="<?php _e('Please enter your full name', 'multi-task') ?>">
            </div>
            <div class="form-field email">
                <label for="person-email"><?php _e('Email:', 'multi-task'); ?></label>
                <input id="person-email" required type="email" name="user-email" placeholder="<?php _e('Please enter your email', 'multi-task') ?>">
            </div>
            <div class="form-field phone">
                <label for="person-phone"><?php _e('Phone:', 'multi-task'); ?></label>
                <input id="person-phone" required type="tel" name="user-phone" placeholder="<?php _e('Please enter your phone number', 'multi-task') ?>">
            </div>
            <div class="form-field member-id">
                <label for="person-member-id"><?php _e('Membebr ID:', 'multi-task'); ?></label>
                <input id="person-member-id" required type="text" name="member-id" placeholder="<?php _e('Please enter your member ID', 'multi-task') ?>">
            </div>            
        </div>
        <div class="responses-message"></div>
        <!-- hidden input starts -->
        <input id="HIDDEN-action" type="hidden" name="action" value="zest_generate_opt">
        <input id="HIDDEN-vote-nonce" type="hidden" name="vote-nonce" value="<?php echo wp_create_nonce('vote-nonce'); ?>">
        <input id="CHECK-otp-verification" type="hidden" name="is-otp-verified" value="no">
        <!-- hidden input ends -->

        <div class="generate-otp">
            <button type="button"><?php _e('Generate OTP','multi-task'); ?></button>
        </div>
        <div class="otp-verify">
            <br>
            <p><?php _e('Please verify OTP Number sent it to you email address.') ?></p>
            <input type="number" name="otp-verification">
            <button type="button"><?php _e('Verify OTP','multi-task'); ?></button>
        </div>
        <div class="submit-vote">
            <button type="submit"><?php _e('Submit Vote','multi-task'); ?></button>
        </div>
    </form> 
</div>