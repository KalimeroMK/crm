# CRM Web Service Package

## Преглед

CRM пакетот е дизајниран за да овозможи безбедна комуникација помеѓу клиентот и Централниот Регистар, овозможувајќи 
пристап до податоци за правни лица. Сервисот нуди два главни продукти: `LEOSSCurrentView` и `AAListingForInsightSolution`, кои овозможуваат пристап до тековни податоци за ентитетот и годишни финансиски извештаи, соодветно.

## Автентикација и Сертификација

### Барања за Дигитален Сертификат
- Секое барање кон сервисот мора да содржи **квалификуван дигитален потпис**.
- Сертификатот мора да биде **2048-битен RSA** и издаден од овластени сертификациски тела во Македонија, како што се **КИБС АД Скопје** или **Македонски Телеком**.
- Сертификатот треба да се користи за **автентикација на клиентот**, а јавните клучеви на клиентот и коренските сертификати мора да бидат доставени до Централниот Регистар пред почетокот на размената на податоци.

### Автентикација Базирана на Сертификат
- Автентикацијата се врши преку проверка на јавниот клуч на дигиталниот потпис вклучен во секое барање.
- Дигиталниот потпис мора да биде генериран со валиден, признат сертификат, кој мора да се ажурира во случај на било какви промени.

- EOSSCurrentView се користи за да се добијат тековни податоци за правно лице користејќи го неговиот **LEID** 
(уникатен идентификациски број на ентитетот).
- AAListing се користи за да се добијат годишни финансиски извештаи за правно лице користејќи го неговиот **LEID** 
(уникатен идентификациски број на ентитетот) и **годината** за која се бараат податоците.

## Ракување со Грешки

Веб сервисот враќа различни предефинирани пораки за ракување со грешки. Подолу се наведени некои од најчестите пораки за грешки:

| Порака за Грешка                       | Опис                                                                                       |
|----------------------------------------|--------------------------------------------------------------------------------------------|
| **Client certificate is not valid!**    | Сертификатот прикачен на барањето е невалиден.                                             |
| **Error converting client certificate!** | Сертификатот не може да се конвертира во X509 формат за валидација.                        |
| **Authentication failed!**              | Јавниот клуч не се совпаѓа со регистрираниот клуч на сертификатот.                         |
| **Request is not valid!**               | Валидацијата на параметрите во барањето не успеа.                                          |
| **Authorization for the request failed!** | Можни причини вклучуваат неактивен клиент или неовластен пристап до продуктот.             |
| **Daily transaction limit exceeded!**   | Надминат е дозволениот број на дневни трансакции.                                         |
| **Request is not digitally signed!**    | Барањето нема дигитален потпис.                                                           |
| **Signature verification failed!**      | Верификацијата на дигиталниот потпис не успеа.                                             |
| **System error when signing output data!** | Се појави проблем при потпишувањето на одговорот во XML. Контактирајте со Централниот Регистар. |


## Конфигурација
```
<?php

return [

    'CRM' => env('CRM_WSDL_PATH', storage_path('public/wsdl/crm-wsdl.xml')),

    'keys' => [
        'PRIVATE_KEY' => env('CRM_PRIVATE_KEY_PATH', storage_path('keys/private_key.pem')),
        'PUBLIC_CERT' => env('CRM_PUBLIC_CERT_PATH', storage_path('keys/public_cert.pem')),
        'PRODUCT_NAME' => env('CRM_PRODUCT_NAME', 'LEOSSCurrentView'),
        'AA_LISTING' => env('CRM_AA_LISTING', 'AAListing'),
    ],
];
```

## Инсталација

# RESFull API Basics #blueprint

This collection provides a set of API endpoints to perform basic CRUD operations. Below are the details about each request, including the URL and payload structure.

## API Endpoints and Payloads

### 1. LEOSSCurrentView

- **URL**: `domain/api/v1/leoss-current-view`
- **Method**: `POST`
- **Description**: This endpoint allows you to submit data via the request body. A successful request typically returns a `200 OK` or `201 Created` response code.
- **Payload**:
  ```json
  {
    "number": "5484561"
  }
  
response:
```json
{
    "data": {
        "CrmResultItems": [
            {
                "@attributes": {
                    "TemplateName": "CVLEInfo"
                },
                "CrmResultItem": {
                    "InfoMessage": {},
                    "IsLETerminated": "True",
                    "LEID": "5484561",
                    "LEFullName": "Друштво за трговија на големо и мало,услуги и шпедиција МАП Пивара ДООЕЛ Скопје",
                    "TaxPayerNumber": "4030000431924",
                    "ShortName": "МАП Пивара ДООЕЛ Скопје",
                    "TerminationTypeID": "5",
                    "TerminationTypeDesc": "По пат на ликвидација",
                    "TerminationBasis": {},
                    "DateDeletedInCR": "11.09.2007 11:20",
                    "LETypeID": "4",
                    "LETypeDesc": "ДООЕЛ",
                    "LESizeID": "2",
                    "LESizeDesc": "мал",
                    "Municipality": "ГАЗИ БАБА",
                    "MunicipalityCode": "27",
                    "Place": "СКОПЈЕ - ГАЗИ БАБА",
                    "PlaceCode": "491012",
                    "Street": "810",
                    "StreetCode": "49101208100",
                    "HouseNo": "3А",
                    "EntranceNo": {},
                    "FlatNo": {},
                    "OrganisationTypeCode": "05.4",
                    "OrganisationTypeDesc": "друштво со ограничена одговорност основано од едно лице",
                    "AuthorisedRegisterID": "1",
                    "AuthorisedRegister": "Трговски Регистар",
                    "OwnershipTypeID": "4",
                    "OwnershipTypeDesc": "Приватна",
                    "IsForeignAct": "True",
                    "IsActivityNoLicence": "False",
                    "CoreActivityCode": "60.24/0",
                    "CoreActivityDesc": "Превоз на стоки во друмскиот сообраќај",
                    "Licence": {},
                    "ForeignActivity": "Регистрирани дејности во надворешно-трговскиот промет",
                    "Email": {},
                    "AdditionalInfo": "НОВО со РАЗДВОЈУВАЊЕ од 04186583 од 28.12.2000 година\nБРИШЕЊЕ:на субјектот од Трговскиот регистар по спроведена ликвидациона постапка согласно чл.657 од ЗТД(Сл.в.на РМ 28/96)по Решение П.Трег.бр.5504/2002 од 6.12.2002 година од Основен суд Скопје I во Скопје ",
                    "IsDataConfirmed": "True",
                    "IsAAActive": "0",
                    "PKDStatus": {},
                    "ActingPeriod": {},
                    "LEStatus": {},
                    "LEStatusDateFrom": {},
                    "LEStatusDateUntil": {},
                    "CourtRegion": {}
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVUnits"
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVActors"
                },
                "CrmResultItem": {
                    "LEID": "5484561",
                    "UnitNo": "0",
                    "PersonOrLEID": "1",
                    "PersonOrLEDesc": "Физичко лице",
                    "ActorID": "1501947450037",
                    "ActorTypeID": "1",
                    "ActorTypeDesc": "Овластено лице",
                    "ActorName": "РАТКО",
                    "ActorSurname": "ЧАДИКОВСКИ",
                    "CountryCode": "MK",
                    "Country": "МАКЕДОНИЈА",
                    "Municipality": "КИСЕЛА ВОДА",
                    "Place": "СКОПЈЕ",
                    "Street": "БУЛ.ЈАНЕ САНДАНСКИ",
                    "HouseNo": "90",
                    "EntranceNo": "5",
                    "FlatNo": "5",
                    "Email": {},
                    "Description": {},
                    "Restrictions": {},
                    "AuthorisationTypeID": {},
                    "AuthorisationTypeDesc": {},
                    "PKDStatus": {}
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVOwners"
                },
                "CrmResultItem": {
                    "LEID": "5484561",
                    "Note": {},
                    "PersonOrLEID": "2",
                    "PersonOrLEDesc": "правно лице",
                    "OwnerID": "4053974",
                    "OwnerTypeID": "6",
                    "OwnerTypeDesc": "Основач/сопственик",
                    "LiabilityID": "1",
                    "LiabilityDesc": "Не одговара",
                    "OwnerName": "\"ПИВАРА СКОПЈЕ\"",
                    "OwnerSurname": {},
                    "CountryCode": "MK",
                    "Country": "МАКЕДОНИЈА",
                    "Municipality": "ГАЗИ БАБА",
                    "Place": "СКОПЈЕ",
                    "Street": "808",
                    "HouseNo": "12",
                    "EntranceNo": {},
                    "FlatNo": {},
                    "Email": {},
                    "FCCode": "MKD",
                    "ParticipationFC_Cash": {},
                    "ParticipationFC_NonCash": "52332031.00",
                    "ParticipationFC_Payd": {},
                    "ParticipationFC_Total": "52332031.00",
                    "PKDStatus": {}
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVActivities"
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVMembership"
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVFounding"
                },
                "CrmResultItem": {
                    "LEID": "5484561",
                    "FoundingDate": "23.11.2000 00:00",
                    "CapitalOriginID": {},
                    "CapitalOriginDesc": {},
                    "FCCode": "MKD",
                    "CapitalFC_Cash": {},
                    "CapitalFC_NonCash": "52332031.00",
                    "CapitalFC_Payd": {},
                    "SharesTotal": {},
                    "SharesPayd": {},
                    "TypeOfShares": {},
                    "SharesPayment": {},
                    "CapitalFC_Total": "52332031.00"
                }
            },
            {
                "@attributes": {
                    "TemplateName": "CVLECourt"
                }
            }
        ],
        "Signature": {
            "SignedInfo": {
                "CanonicalizationMethod": {
                    "@attributes": {
                        "Algorithm": "http://www.w3.org/TR/2001/REC-xml-c14n-20010315"
                    }
                },
                "SignatureMethod": {
                    "@attributes": {
                        "Algorithm": "http://www.w3.org/2000/09/xmldsig#rsa-sha1"
                    }
                },
                "Reference": {
                    "@attributes": {
                        "URI": ""
                    },
                    "Transforms": {
                        "Transform": {
                            "@attributes": {
                                "Algorithm": "http://www.w3.org/2000/09/xmldsig#enveloped-signature"
                            }
                        }
                    },
                    "DigestMethod": {
                        "@attributes": {
                            "Algorithm": "http://www.w3.org/2000/09/xmldsig#sha1"
                        }
                    },
                    "DigestValue": "94zmfGBPxyEE+Zfpqcf1kJzR7/c="
                }
            },
            "SignatureValue": "i9nqRotbuLvUPqzkTNJYseQpAp57BUp000nQKr3Sy0H2WpqR7+ViV8X/bi2RSz8UiLh8Hn0jJu42HcRBGvmipQaJbGFKUDxkkRrkhjnryiYFT+NIZ9SRRVP/f8/3DW4RwsZxfyufMvDM/wwprZADwJaOGZaJNu40HJ8WKU5dZM+Cm8TM9pqDcO1zLi4e8vwz+xifftJxBYxOUrb4Cg3XZ0AqPibkMN+gAaJHl2wYTCi74vxsupiF4BgxA4Q95yan7zC17rCBgxaul7UBY2I/FtT7jrWsPMCaRbz/nLkOH+Nb7jKJL4q90JylDgz21E0Y4uC0fq/7iy5k+eu4ayPPRg==",
            "KeyInfo": {
                "X509Data": {
                    "X509Certificate": "MIIIETCCBnmgAwIBAgIRAMZHe4d8cZuXAAAAAF8miqowDQYJKoZIhvcNAQELBQAwSjELMAkGA1UEBhMCTUsxGzAZBgNVBAoTEk1ha2Vkb25za2kgVGVsZWtvbTEeMBwGA1UEAxMVTWFrZWRvbnNraSBUZWxla29tIENBMB4XDTI0MDMyMDEwNDM1OFoXDTI2MDMyMDExMTM1OFowge0xCzAJBgNVBAYTAk1LMVcwGgYDVQRhExNWQVRNSy00MDMwMDAxNDI1NDgwMDkGA1UEChMyQ0VOVFJBTEVOIFJFR0lTVEFSIE5BIFJFUFVCTElLQSBTRVZFUk5BIE1BS0VET05JSkExSTBHBgNVBAsTQENFTlRSQUxFTiBSRUdJU1RBUiBOQSBSRVBVQkxJS0EgU0VWRVJOQSBNQUtFRE9OSUpBOjQwMzAwMDE0MjU0ODAxOjARBgNVBAUTCkNSVDM3MjMzNTAwJQYDVQQDEx5DUlJTTSBTeXN0ZW0gLSBYTUwgV2ViIFNlcnZpY2UwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCpzQ6Gy4r+wxDYGeNJezKWWt3QO04WDGK6NhAKIiG3f0GggzhtHKrMa0Nav1cCcMEv2qflESl11F/U6K2a5+AHN3x0rM+Ty8nAQX2Dju0vCXya1HfC8uIclQoPfn1JY93EuYGDmG5xayc3c6sJwEoW9sM59p2jQW1hUz2FaAU90n1ah3veE2QkC8n6JuOzWl1t4SQHxEZcv6MQz8/ffyTNz+07s/OBrg3uNqnkS9YqMgAoZsCRRYaODYitbebTuCaX0KxgNSqCAWF0PU5H93fGVbbOksrsQU6p2kom9OVVy+uVCjCY7q45q/n1fsK+VAS0gzGG1cSOyxY7pw5en72tAgMBAAGjggPMMIIDyDAOBgNVHQ8BAf8EBAMCBeAwDAYDVR0TAQH/BAIwADBQBgNVHSAESTBHMDoGDisGAQQBgZEAAQUFAAAAMCgwJgYIKwYBBQUHAgEWGmh0dHA6Ly93d3cudGVsZWtvbS5tay9DUFMvMAkGBwQAi+xAAQEwgfcGCCsGAQUFBwEDBIHqMIHnMAgGBgQAjkYBATCBtQYGBACORgEFMIGqMFMWTWh0dHBzOi8vaWN0LnRlbGVrb20ubWsvY29udGVudC9kb2t1bWVudGktZGlnaXRhbG5pLXNlcnRpZmlrYXRpL1BEU19NS1RfRU4ucGRmEwJlbjBTFk1odHRwczovL2ljdC50ZWxla29tLm1rL2NvbnRlbnQvZG9rdW1lbnRpLWRpZ2l0YWxuaS1zZXJ0aWZpa2F0aS9QRFNfTUtUX01LLnBkZhMCbWswEwYGBACORgEGMAkGBwQAjkYBBgIwDgYGBACORgEHMAQTAk1LMIGCBggrBgEFBQcBAQR2MHQwSwYIKwYBBQUHMAKGP2h0dHA6Ly93d3cuY2EudGVsZWtvbS5tay9jYWNlcnQvTWFrZWRvbnNraVRlbGVrb21DQV9jYWNlcnQxLmNlcjAlBggrBgEFBQcwAYYZaHR0cDovL29jc3AuY2EudGVsZWtvbS5tazAXBgorBgEEAYGRAAIBBAkWBzU1NDk4NTAwHgYLKwYBBAGBkQACAQIEDxYNNDAzMDAwMTQyNTQ4MDAbBgsrBgEEAYGRAAIBAQQMFgpDUlQzNzIzMzUwMB0GA1UdEQQWMBSBEmNyX2luZm9AY3JtLm9yZy5tazCCATgGA1UdHwSCAS8wggErMGKgYKBepFwwWjELMAkGA1UEBhMCTUsxGzAZBgNVBAoTEk1ha2Vkb25za2kgVGVsZWtvbTEeMBwGA1UEAxMVTWFrZWRvbnNraSBUZWxla29tIENBMQ4wDAYDVQQDEwVDUkw4MTCBxKCBwaCBvoY1aHR0cDovL3d3dy5jYS50ZWxla29tLm1rL0NSTC9NYWtlZG9uc2tpVGVsZWtvbUNBMS5jcmyGgYRsZGFwOi8vbGRhcC1jYS5jYS50ZWxla29tLm1rL2NuPVdpbkNvbWJpbmVkMSxjbj1NYWtlZG9uc2tpJTIwVGVsZWtvbSUyMENBLG89TWFrZWRvbnNraSUyMFRlbGVrb20sYz1NSz9jZXJ0aWZpY2F0ZVJldm9jYXRpb25MaXN0P2Jhc2UwEwYDVR0jBAwwCoAIT2WO5HXAyycwEQYDVR0OBAoECE9cktYBo3zKMA0GCSqGSIb3DQEBCwUAA4IBgQBMN5lUcXNBZ7T+DunaNE7g/AVl+pAhnLrJ72EUUdpl8yAwsZuuZrqULiamCkqRola4WRH9P1+VZAsTf6DOSrZ2/fjrU5Sgc4D3Js4UB7lH6dJk9csiYMC7tCH2zKX17Rxs6b/L1u74/uKdcdz2//ScWJXEtjH6Lq7rVbO/hEJzCZZxMWdssEardXMXRhpbgXsbTrL9E96KeJF/v6lTVneyJoc5n8uDJCIeeYZkSyZqq+9v/r/BfeBMq+grWggkf2z24ehCHAFC8sgWkOvqBhlfy1zdGIKn/tlx+ExHA0MH1h1MDVHUMhqvbYnBojDzSThhI6o9naGOLylgDC+J+WEZ1pZDY+VZdZyJZyeTSG0sVkI1jxAHda8vkUii7fnJDykiPE1u0pKQnms39LL1NMPc0BjMuRPL2iQR2v7pl97xwQ4IZVRcOwCqbRGQBD7o20RzKvW+gmG74SDVFy44J0dnmxNVH4GpupOeZURdaZRc0Ucvf/vG5nv6sY9WVwfO9cs="
                }
            }
        }
    }
}
```
  

### 2. AAListing

- **URL**: `domain/api/v1/laa-listing`
- **Method**: `POST`
- **Description**: This endpoint allows you to submit data via the request body. A successful request typically returns a `200 OK` or `201 Created` response code.
- **Payload**:
  ```json
  {
    "number": "5484561",
    "date": "2023"
  }

response:
```json
{
  "data": {
    "CrmResultItems": [
      {
        "@attributes": {
          "TemplateName": "CVLEInfo"
        },
        "CrmResultItem": {
          "InfoMessage": "OK",
          "Year": "2022",
          "LEID": "5484561",
          "LEFullName": "Друштво за трговија на големо и мало,услуги и шпедиција МАП Пивара ДООЕЛ Скопје",
          "Place": "СКОПЈЕ - ГАЗИ БАБА",
          "SubProductID": "3",
          "SubProductInfo": "Субјектот нема доставено годишна сметка или нема доставено известување дека нема деловна активност во 2022 година."
        }
      },
      {
        "@attributes": {
          "TemplateName": "AAListingInfo"
        },
        "CrmResultItem": {
          "InfoMessage": "Производот не е достапен за дадените влезни параметри",
          "Year": "2022",
          "LEID": "5484561",
          "OperationID": {},
          "AccountNo": {},
          "AccountName": {},
          "Previous": {},
          "CurrentYear": {}
        }
      }
    ],
    "Signature": {
      "SignedInfo": {
        "CanonicalizationMethod": {
          "@attributes": {
            "Algorithm": "http://www.w3.org/TR/2001/REC-xml-c14n-20010315"
          }
        },
        "SignatureMethod": {
          "@attributes": {
            "Algorithm": "http://www.w3.org/2000/09/xmldsig#rsa-sha1"
          }
        },
        "Reference": {
          "@attributes": {
            "URI": ""
          },
          "Transforms": {
            "Transform": {
              "@attributes": {
                "Algorithm": "http://www.w3.org/2000/09/xmldsig#enveloped-signature"
              }
            }
          },
          "DigestMethod": {
            "@attributes": {
              "Algorithm": "http://www.w3.org/2000/09/xmldsig#sha1"
            }
          },
          "DigestValue": "F7K45hzvyBF3YClWGLbh4R1r4N0="
        }
      },
      "SignatureValue": "Bw1Icj2tf7tY9UGaAznj5xWHyKcrlocuHyy8cyreiuRPCaG2nQdh/aKFR77bTfM0m1KcOBoorQqG/W9vVVsarwpaPlN/X07wuo/icBi/Nsa0BNKjCaiUUq5h/4DFQtRRO68LTdUhVcEZj/PFgq220oQwOFcA4HaItUrsx5bCoeaqX+dWs9hYZXmlNC6AiwBo/nlAL9PdsUiXDF8nJC1Wi/rwFD87lFm42tFnLrmdwYbRDFlN1Z0xo5rwPPvqISdjl93fSNyzOus8rT4ldFWN80TdZk0LTQzhoeL8OJid3tVNzRen6BkzX6Q94AbpAPEnyBAS8ocIFV7EKN5+pFMKuQ==",
      "KeyInfo": {
        "X509Data": {
          "X509Certificate": "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"
        }
      }
    }
  }
}
```
