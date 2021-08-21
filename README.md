# Backend Assessment

## **Task 1**

Describe what you think happened that caused those bad reviews during our 12.12 event and why it happened.

- Yang terjadi pada saat flash sale 12.12 adalah meningkatnya kunjungan dari user yang mengakibatkan meningkatnya juga order / pesanan dalam waktu bersamaan.
Update stock secara realtime harus dilakukan jika website / aplikasi dalam event spesial seperti flash 12.12 misalnya.

Based on your analysis, propose a solution that will prevent the incidents from occurring again.

- Solusi yang pertama adalah dengan inventory stock yang bisa disimpan di level cache memory dengan menggunakan redis. Dimana bila akan ada flash sale, product yang terdaftar di flash sale akan disimpan juga secara inventory pada level cache (redis) untuk mempercepat baca data.

- Solusi yang kedua adalah dengan menggunakan sistem threshold (dengan menambahkan field threshold pada table product di database) dimana jika telah mencapai titik quantity tertentu maka sistem otomatis akan mengupdate product dengan status "out of stock" 

Untuk itu saya mencoba membuat API sederhana dengan struktur data sederhana sebagai berikut : 

![image](https://user-images.githubusercontent.com/2240305/130315974-89b88fa5-80af-4ad0-ac10-540223f7921d.png)

Lalu berikut adalah skema API yang digunakan :

https://www.getpostman.com/collections/34269061282b4a9a7f50

**~POST~**

**Create User (/user)**

Untuk create user

**Create Cart (/cart)**

Untuk create cart 

**Create Order (/order)**

Untuk create order dengan metode inventory simpan ke dalam redis* (lakukan generate product terlebih dahulu)

**Create Order By Threshold (/orderThreshold)**

Untuk create order dengan metode threshold (flag database threshold)

-----------------------------------------------------

**~GET~**

**Generate Product (/product/generate)**

Untuk generate data inventory db ke redis

**Product (/product)**

Untuk display data product


Penjelasan API Lengkap : https://web.postman.co/documentation/12891880-3692cd37-cfb0-4819-8e8c-4af350287705/publish?workspaceId=bff1c502-636f-466e-87fa-f45651262d51


## **Task 2**

https://github.com/romascudeto/treasure-hunt-evermos
