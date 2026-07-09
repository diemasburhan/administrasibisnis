#!/bin/bash

# Pastikan dijalankan sebagai root
if [ "$EUID" -ne 0 ]; then
  echo "Harap jalankan script ini menggunakan sudo: sudo bash setup_nginx.sh"
  exit 1
fi

DOMAIN="administrasibisnis.lpkia.ac.id"
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"

echo "Membuat konfigurasi Nginx untuk $DOMAIN..."
cat <<EOF > $NGINX_CONF
server {
    server_name $DOMAIN;

    location / {
        proxy_pass http://127.0.0.1:8100;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOF

echo "Mengaktifkan konfigurasi Nginx..."
ln -sf $NGINX_CONF /etc/nginx/sites-enabled/

echo "Melakukan pengetesan konfigurasi Nginx..."
nginx -t
if [ $? -ne 0 ]; then
    echo "Gagal: Terdapat kesalahan pada konfigurasi Nginx."
    exit 1
fi

echo "Me-reload Nginx..."
systemctl reload nginx

echo "Konfigurasi Nginx selesai!"
echo "Sekarang kita akan menjalankan Certbot untuk mendapatkan sertifikat SSL..."
echo "Pastikan DNS A Record untuk $DOMAIN sudah mengarah ke IP Server ini."

certbot --nginx -d $DOMAIN --non-interactive --agree-tos --register-unsafely-without-email
echo "Selesai!"
