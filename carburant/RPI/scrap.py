# import libraries
from bs4 import BeautifulSoup
import requests


# specify the url
urlpage = 'https://www.boursorama.com/bourse/matieres-premieres/cours/8xBRN/'

page = requests.get(urlpage)
# parse the html using beautiful soup and store in variable 'soup'
soup = page.content
soup = BeautifulSoup(soup, 'html.parser')

# determiner le prix du carburant
prix = soup.find(class_ = 'c-instrument c-instrument--last').text.strip()
prix_indicatif = soup.find(class_ = 'c-faceplate__indicative-value').text.strip()
print(prix)
print(prix_indicatif)
prix_indicatif = prix_indicatif.replace('EUR', '')
print(prix_indicatif)
