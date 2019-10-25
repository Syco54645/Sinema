from imdb import IMDb
import sys
import json

# create an instance of the IMDb class
ia = IMDb()

#get the imdbid from the command
imdbId = str(sys.argv[1])

# get movie keywords
movie = ia.get_movie(imdbId, info=['keywords'])

# for keyword in movie['keywords']:
#     print(keyword)

# convert the keywords to json
print json.dumps(movie['keywords'])