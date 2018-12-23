#!/bin/bash
pg_dump -h 172.18.0.7 -U postgres  jogadores > /var/www/html/PaintballSocialNetwork-Players/dump/$(date +"%Y%m%d").sql
