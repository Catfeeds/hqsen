### 客户端 API
--------------------------------
#### 获取验证码接口
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=user&f=getPhoneCode](http://dev.51isen.com/index.php?m=app&c=user&f=getPhoneCode) 
* 接口方法 post  
* 接口参数 mobile(必填 手机号 15068159661)  
* 接口返回

        {
          "status": 200,
          "data": {
            "mobile": "15068159661",
            "code": "2312"
          },
          "message": "请求成功"
        }

--------------------------------
#### 登录接口
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=user&f=login](http://dev.51isen.com/index.php?m=app&c=user&f=login) 
* 接口方法 post  
* 接口参数 phone(必填 手机号 15068159661) | code (必填 验证码 3452) 
* 接口返回

        {
          "status": 200,
          "data": {
            "access_token": 2,
            "alipay_account": "15068159661",
            "nike_name": "monkey肖",
            "user_type": 2
          },
          "message": "请求成功"
        }

--------------------------------
#### 更新接口(登录 唤醒 重新打开调用 返回基础参数)
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=sysData&f=updateData](http://dev.51isen.com/index.php?m=app&c=sysData&f=updateData) 
* 接口方法 post  
* 接口参数 access_token(必填 登录返回access_token 1233232)
* 接口返回

        {
          "status": 200,
          "data": {
            "order_type": [
              {
                "type": "1",
                "type_name": "婚宴"
              },
              {
                "type": "2",
                "type_name": "满月"
              },
              {
                "type": "3",
                "type_name": "订婚"
              }
            ],
            "order_area": [
              {
                "area": "1",
                "area_name": "普陀区",
                "area_hotel": [
                  {
                    "hotel_id": 1,
                    "hotel_name": "希尔顿酒店"
                  },
                  {
                    "hotel_id": 2,
                    "hotel_name": "喜来登酒店"
                  }
                ]
              },
              {
                "area": "2",
                "area_name": "浦东区"
              },
              {
                "type": "3",
                "type_name": "指定酒店"
              }
            ],
            "update_status": {
              "version": "1.0"
            }
          },
          "message": "请求成功"
        }

--------------------------------
#### 支付宝绑定接口
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=user&f=alipayBind](http://dev.51isen.com/index.php?m=app&c=user&f=alipayBind) 
* 接口方法 post  
* 接口参数 alipay (必填 支付宝帐号 15068159661) | access_token(必填 登录返回access_token 1233232)
* 接口返回

        {
          "status": 200,
          "data": [],
          "message": "success"
        }

--------------------------------
#### 验证订单类型的手机 还能不能创建
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=order&f=validatePhoneOrderType](http://dev.51isen.com/index.php?m=app&c=order&f=validatePhoneOrderType) 
* 接口方法 post  
* 接口参数 order_type(必填 客资订单类别 1婚宴 2会务 3宝宝宴) | order_phone  (必填 手机号 15068159661) | access_token(必填 登录返回access_token 1233232)
* 接口返回 

        {
          "status": 200,
          "data": [],
          "message": "success"
        }
--------------------------------

#### 创建确定类型的客资信息
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=order&f=createKeZi](http://dev.51isen.com/index.php?m=app&c=order&f=createKeZi) 
* 接口方法 post  
* 接口参数 order_type(必填 客资订单类别 1婚宴 2会务 3宝宝宴) | order_phone  (必填 手机号 15068159661) | access_token(必填 登录返回access_token 1233232) | order_area_hotel_type(必填 指定区域或酒店 1区域 2酒店) | order_area_hotel_id(必填 区域ID 或则酒店ID) |customer_name(选填 消费者 张三)  |desk_count(选填 桌数 66)|use_date(选填 使用时间 12-09-09)|order_money(选填 金额 18万)|order_desc(选填 订单简介 简介内容)
* 接口返回 

        {
          "status": 200,
          "data": [],
          "message": "success"
        }
--------------------------------

#### 获取客资信息列表
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=order&f=orderKeZiList](http://dev.51isen.com/index.php?m=app&c=order&f=orderKeZiList) 
* 接口方法 get  
* 接口参数 access_token(必填 登录返回access_token 1233232) | order_page(选填 分页 1) | order_status(选填 订单状态 1全部 2待处理 3跟踪 4待结算 5已结算 6已取消)
* 接口返回 

        {
          "status": 200,
          "data": {
            "order_list": [
              {
                "id": 116,
                "create_time": "1490500560",
                "order_status": 1,
                "order_phone": "186 2736 1728",
                "watch_user": "上海国际饭店"
              },
              {
                "id": 116,
                "create_time": "1490500560",
                "order_status": 1,
                "order_phone": "186 2736 1728",
                "watch_user": "上海国际饭店"
              },
              {
                "id": 116,
                "create_time": "1490500560",
                "order_status": 1,
                "order_phone": "186 2736 1728",
                "watch_user": "上海国际饭店"
              }
            ]
          },
          "message": "请求成功"
        }
--------------------------------


#### 根据ID获取客资信息
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=order&f=orderKeZiDetail](http://dev.51isen.com/index.php?m=app&c=order&f=orderKeZiDetail) 
* 接口方法 get  
* 接口参数 access_token(必填 登录返回access_token 1233232) | order_id (必填 订单ID)
* 接口返回 

        {
          "status": 200,
          "data": {
            "order_item": {
              "id": 6,
              "create_time": "1491535065",
              "order_status": 1,
              "order_phone": "15068148661",
              "watch_user": "",
              "customer_name": "大熊",
              "order_type": 1,
              "order_area_hotel_type": 2,
              "order_area_hotel_id": 43,
              "desk_count": "5",
              "order_money": "12",
              "use_date": "15",
              "order_desc": "desccccccccccccc",
              "order_area_hotel_name": "希尔顿"
            }
          },
          "message": "请求成功"
        }
        
--------------------------------

#### 意见反馈
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=feedback&f=create](http://dev.51isen.com/index.php?m=app&c=feedback&f=create) 
* 接口方法 post 
* 接口参数 access_token(必填 登录返回access_token 1233232) | content(必填 内容 啦啦啦啦测试意见反馈内容) |phone(选填 手机号 123453334)
* 接口返回 

        {
          "status": 200,
          "data": [],
          "message": "success"
        }
--------------------------------

#### 获取区域酒店列表
* 接口地址 [http://dev.51isen.com/index.php?m=app&c=order&f=orderHotelArea](http://dev.51isen.com/index.php?m=app&c=order&f=orderHotelArea) 
* 接口方法 post 
* 接口参数 access_token(必填 登录返回access_token 1233232) | hotel_area_type(必填 区域或酒店 1区域 2酒店) 
* 接口返回 

        {
          "status": 200,
          "data": [
            {
              "hotel_id": "45",
              "hotel_name": "如家"
            },
            {
              "hotel_id": "44",
              "hotel_name": "如家"
            },
            {
              "hotel_id": "43",
              "hotel_name": "希尔顿"
            }
          ],
          "message": "请求成功"
        }
--------------------------------