### 客户端 API
--------------------------------
#### 登录接口
* 接口地址 [http://dev.meiui.me/index.php?m=app&c=user&f=getPhoneCode](http://dev.meiui.me/index.php?m=app&c=user&f=getPhoneCode) 
* 接口方法 post  
* 接口参数 mobile  
* 接口返回

        {
            status: 200,
            data: {
                mobile: "15068159661",
                code: "2312"
            },
            message: "请求成功"
        }

--------------------------------
#### 登录接口
* 接口地址 [http://dev.meiui.me/index.php?m=app&c=user&f=login](http://dev.meiui.me/index.php?m=app&c=user&f=login) 
* 接口方法 post  
* 接口参数 phone | code  
* 接口返回

        {
             status: 200,
             data: {
                 access_token: 2,
                 alipay_account: "15068159661",
                 nike_name: "monkey肖",
                 user_type: 2
             },
             message: "请求成功"
         }

--------------------------------