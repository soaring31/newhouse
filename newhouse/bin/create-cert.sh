#!/bin/sh

openssl genrsa -out CA.key 2048
openssl req -x509 -new -nodes -key CA.key -days 1460 -subj '/CN=Swiftmailer CA/O=Swiftmailer/L=Paris/C=FR' -out CA.crt
openssl x509 -in CA.crt -clrtrust -out CA.crt

openssl genrsa -out sign.key 2048
openssl req -new -key sign.key -subj '/CN=Swiftmailer-User/O=Swiftmailer/L=Paris/C=FR' -out sign.csr
openssl x509 -req -in sign.csr -CA CA.crt -CAkey CA.key -out sign.crt -days 1460 -addtrust emailProtection
openssl x509 -in sign.crt -clrtrust -out sign.crt

rm sign.csr

#生成私钥
openssl genrsa -out rsa.pem 2048

#Java开发者需要将私钥转换成PKCS8格式
openssl pkcs8 -topk8 -inform PEM -in rsa.pem -outform PEM -nocrypt -out rsa_pkcs8.pem

#生成公钥
openssl rsa -in rsa.pem -pubout -out rsa.crt