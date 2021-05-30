<div style="padding: 30px;">
    <div style="
        padding: 20px;
        border-bottom: 1px solid #ccc;
    ">
        <h2 style="
            margin: 0;
            color: #65bb38;
        ">
            AIRAGRI
        </h2>
    </div>
    <div style="padding: 20px;">
        <div>
            <p>Hello <?= $user; ?>,</p>
        </div>
        <div>
            <p>Welcome to AirAgri!</p>
            <p>To activate your account please click the butotn below to verify your email address:</p>
        </div>
        <div
            style="text-align: center;
                margin-top: 30px;
                margin-bottom: 30px;"
        >
            <a href="<?= $url ?>" 
                style="text-decoration: none;
                background: #65bb38;
                font-weight: 600;
                color: #fff;
                padding: 15px;"
            >
                Activate Account
            </a>
        </div>
        <div>
            <p>Or paste this link into your browser:</p>
            <a href="<?= $url ?>"><?= $url ?></a>
            <p>Happy!</p>
        </div>
    </div>
</div>
