# dcoin-API
디지텍코인 (이하 D코인)의 오픈 API입니다.

# 기능
거래내역 확인, 송금, 대출, 대출내역 확인, 유저 인증 및 토큰 발급

## 에러코드
* 001 토큰 불일치
* 002 ID/PW 불일치

# flow
## Get Code
```
GET http://coin.digitech.wiki/auth/login
  ?app_idx={$app_idx}
  &redirect_url={$redirect_url}"
```
returns ``{redirect_url}?code={CODE}``

## Get AccessToken
```
GET/POST http://coin.digitech.wiki/auth/access_token
  app_idx={app_idx},
  app_secret_code={app_secret_code},
  code={code},
  expires=60*60*24
```
returns 
```
{
  "access_token": "ACCESS-_TOKEN"
}
```
OR 
```
{
  "error": {
    "type": "error_type",
    "message": "Error message"
  }
}
```
