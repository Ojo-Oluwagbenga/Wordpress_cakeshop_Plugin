<div class="plugin-tab">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <div class="head">
        Plugin Setup
    </div>

    <div style="color: red; font-weight:bold"><?php if (!$right){echo "Kindly reconfigure the key properly";} ?></div>
    
    <div class="authpack" id='authapi'>
        <h3 class="authtype">Authorize by API key</h3>
        <div style="display: flex; justify-content: space-between; margin: 10px 0px;">
            <input  style="width: 70%" type="password" placeholder="Enter your API token" value="<?php echo $key ?>">
            <div style="width: 20%" type='api' class="c-vert confirm">Confirm</div>
        </div>    
    </div>

    <div class="authpack" id='authcred'>
        <h3 class="authtype">Authorize by Credentials</h3>
        <div style="display: flex; justify-content: space-between; margin: 10px 0px;">
            <div style="width: 45%">
                <h4 >Username</h4>
                <input id='username' type="text" placeholder="Enter username">
            </div>
            <div style="width: 45%" >
                <h4>Password</h4>
                <input  id='password' type="password" placeholder="Enter password">
            </div>
        </div>  
        <div style="width: 100%" type='cred' class="c-vert confirm">Confirm</div>       
    </div>

    <script>
        $('.confirm').click(function(){
            let mdata = {};
            if ($(this).attr('type') == 'cred'){
                let uname = $("#authcred #username").val();
                let password = $("#authcred #password").val();

                if (uname != '' && password != ''){
                    mdata = {
                        username : uname,
                        password: password,
                        type: 'cred'
                    }
                }else{
                    alert('Please enter all credentials');
                }
                

            }
            
            if ($(this).attr('type') == 'api'){
                mdata = {
                    type: 'api',
                    apikey : $('#authapi input').val(),
                }
            }

            mdata['action'] = 'plugin_auth';


            let req = $.ajax({
                url: '<?php echo admin_url('admin-ajax.php')?>',
                method: "POST",
                data: mdata,
            });
            req.done(function( msg ) {
                alert(msg);
            });
            req.fail(function(jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });
        })

        

    </script>
    
</div>

<style>
    .c-vert{
        display: flex;
        justify-content: center;
        flex-direction:column;
    }
    .head{
        padding: 25px 10px;
        font-weight: bold;
        font-size: 25px;
        border-bottom: 2px solid grey;
    }
    .authpack *{
        box-sizing: border-box;
    }
    .authpack{
        margin: 10px auto;
        margin-top: 45px;
        max-width: 60%;
    }
    .authpack input{
        outline: none;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .authpack .confirm{
        border-radius: 10px;
        padding: 10px;
        background-color: green;
        text-align: center;
        min-height: 50px;
        min-width: max-content;
        color: white;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .authpack .confirm:hover{
        background-color: rgb(5, 41, 5);
    }
    .authpack h4{
        padding: 5px;
        margin:0px;
    }
</style>
