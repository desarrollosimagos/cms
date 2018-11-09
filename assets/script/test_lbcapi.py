# encoding: utf-8

import sys

from lbcapi import api

import json

# Argumentos recibidos desde php (clave, clave secreta y ruta de la api)
hmac_key = sys.argv[1]

hmac_secret = sys.argv[2]

api_url = sys.argv[3]

# Cargamos los datos básicos del usuario correspondiente a la autenticación HCMAC
#~ conn = api.hmac(hmac_key, hmac_secret)
#~ conn = conn.call('GET', '/api/myself/').json()

# Cargamos los datos básicos del wallet correspondiente a la autenticación HCMAC
#~ conn = api.hmac(hmac_key, hmac_secret)
#~ conn = conn.call('GET', '/api/wallet/').json()

# Cargamos la lista de las transacciones cerradas correspondientes a la autenticación HCMAC
conn = api.hmac(hmac_key, hmac_secret)
conn = conn.call('GET', api_url).json()
# Convertimos el objeto python a formato json usando json.dumps, 
# ya que sin esto el resultado sería una cadena con formato parecido pero incorrecto
conn = json.dumps(conn)
print conn
