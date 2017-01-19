# author
[@mskims](http://github.com/mskims)

# dcoin-API
디지텍코인 (이하 D코인)의 오픈 API입니다.

# 기능
거래내역 확인, 송금, 대출, 대출내역 확인, 유저 인증 및 토큰 발급

## 에러코드
* 001 토큰 불일치
* 002 ID/PW 불일치

# 샘플 사이트
http://bet.kimminseok.info

ID ``01012341234``
PW ``1234``

ID ``01012345678``
PW ``1234``

# flow
## Get Code (Auth)
```
GET https://coin.digitech.wiki/auth/login
  app_idx={app_idx},
  redirect_url={redirect_url}
```
returns ``{redirect_url}?code={CODE}``

## Get AccessToken
```
GET/POST https://coin.digitech.wiki/auth/access_token
  app_idx={app_idx},
  client_secret={app_secret_code},
  code={code},
  expires=60*60*24
```
returns 
```
access_token=Y89uJjP-PRQCFs1Q7jOmde03XK77ms45o2qNiPBJqsmGbgelYSKKGUPvdYlSKsTMwYDoebNrPdK6WX...
```

## Get AccessToken info
```
GET https://coin.digitech.wiki/api/token/info
  access_token={access_token}
```
returns
```
{
    "user_idx": "1000000001",
    "scopes": [
        "public_info",
        "transfer_history"
    ],
    "created_at": "1475809152",
    "expires_at": "1477809152"
}
```

## Transfer
### Create Transfer
```
GET https://coin.digitech.wiki/api/transfer/create
  user_idx={user_idx},
  access_token={access_token},
  user_to_account_number={user_to_account_number},
  money={money},
  redirect_url={redirect_url}
```
returns
```
{
  "hash": "s1Q7jOmde03XPRQCFs1Q7jOmde03XK77ms45o2qNiPBJqsmGbgelYSKKGUPvdYlSKsTMwYDoebNrPdK6WX.."
}
```
### Auth Transfer
```
GET https://coin.digitech.wiki/auth/transfer
  hash={hash},
  redirect_url={redirect_url}
```
returns ``{redirect_url}?hash={hash}``

### Get Transfer History
```
GET https://coin.digitech.wiki/api/me/transfer_history
  from={from[MIN=0]},
  limit={limit[MAX=10]},
  user_idx={user_idx},
  access_token={access_token}
```
returns
```
[
  {
    "idx": "1",
    "transfer_idx": "114",
    "income": "false",
    "money": "123",
    "balance": "6617",
    "type": "계좌이체",
    "type_memo": "홍길동",
    "memo": "",
    "created_at": "1476667158",
    "from": {
      "name": "길쑨이",
      "account_number": "01012341234"
    },
    "to": {
      "name": "홍길동",
      "account_number": "01012345678"
    }
  },
  {
    "idx": "2",
    "transfer_idx": "113",
    "income": "false",
    "money": "123",
    "balance": "6740",
    "type": "계좌이체",
    "type_memo": "홍길동",
    "memo": "",
    "created_at": "1476666666",
    "from": {
      "name": "길쑨이",
      "account_number": "01012341234"
    },
    "to": {
      "name": "홍길동",
      "account_number": "01012345678"
    }
  }
]
```


## On Error
```
{
  "error": {
    "type": "error_type",
    "message": "Error message"
  }
}
```
