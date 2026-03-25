# pim-api-symfony
Product Information Management (PIM) service with price evolution auditing and soft-delete capabilities.

## Słowo wstępu

Niestety nie wyrobiłem się ze wszystkim w przewidzianych 4h. To, czego nie udało się zrobić w tym czasie to obsługa współbieżności oraz testy. Mimo wszystko postanowiłem dokończyć ten ostatni punkt zadania po czasie oraz napisać przynajmniej przykładowe testy. 

## Decyzje architektoniczne

- Zdecydowałem się oprzeć REST API na API platform, ponieważ jest bezpieczna (i aktualizowana), podąża za najlepszymi standardami i to wszytko przy stosunkowo szybkiej konfiguracji. Zapewnia ponadto swobodę w rozbudowie o niestandardowe zdarzenia oraz zapewnia Swagger, co było jednym z wytycznych.    

- Dla currency i statusów zastosowałem enumy, ponieważ eliminują ryzyku wpisania błędnych wartości, ułatwiają walidację i stanowią źródło prawdy dla dostępnych opcji z możliwością wykorzystania w różnych miejscach w kodzie.

- Wykorzystałem Listnera do zmiany ceny, dzięki czemu mam pewność, że historia ceny zostanie zawsze zapisana, niezależnie od źródła zmiany ceny, a więc czy przez API, CLI, panele administracyjne itd. Stanowi to również odpowiedź na potrzebę wyrażoną: "System zarządza katalogiem produktów, które są synchronizowane z różnymi kanałami sprzedaży"

- Sama logika zmiany ceny została wydzielona do osobnego serwisu ProductPriceManager, co ułatwia testowanie, pozwala na reużywalność kodu i podąża za zasadą single responsible.

- Do obsługi współbieżności zastosowałem pole version w encji produktu, które automatycznie inkrementuje się po aktualizacji zasobu. Proste i skuteczne zabezpieczenie przed aktualizacją wcześniej zaktualizowanego zasobu.

- Zgodnie z zadaniem zostało wyemitowane zdarzenie domenowe, do czego wykorzystałem Symfony Messenger, który w przyszłości, przy dodatkowemu wykorzystaniu, chociażby RabbitMQ, mógłby wnieść asynchroniczność do aplikacji.  

- Walidacje zastosowałem mieszane w zależności od potrzeby. Najczęściej wykorzystując constrainty symfony, a dla SKU custom validatora przypisanego do pola, co zapewni walidację bez względu na źródło zmiany danych.

- Soft delete została obsłużona przez customowy processor API platform, który pozwala na modyfikację zachowania poszczególnych metod API, dzięki czemu api platform będzie pilnowała odpowiedniej procedury usuwania (zmiana statusu zamiast całkowitego usunięcia rekordu).

## Instalacja

```shell
docker compose up -d
make install
```

## Test

```shell
make test
```

## Swagger
Dokumentacja API: [http://localhost:8080/api](http://localhost:8080/api)
