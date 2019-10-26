import sys
import json
from plexapi.server import PlexServer
from pprint import pprint

import sqlite3
from sqlite3 import Error

def create_connection(db_file):
    conn = None
    try:
        conn = sqlite3.connect(db_file)
    except Error as e:
        print(e)

    return conn

def get_setting(conn, slug):
    cur = conn.cursor()
    cur.execute("SELECT setting_value FROM settings WHERE setting_slug=?", (slug,))

    rows = cur.fetchone()

    for row in rows:
        return row

def get_grindhouse(conn, id):
    cur = conn.cursor()
    cur.execute("SELECT name, command, command_version FROM grindhouse WHERE id=?", (id,))

    rows = cur.fetchone()

    return rows


def main():
    database = r"/var/www/html/databases/grindhouse.db"
    grindhouseId = str(sys.argv[1])

    # create a database connection
    conn = create_connection(database)
    with conn:
        #print("1. Query task by priority:")
        baseurl = get_setting(conn, 'plex-url')
        token = get_setting(conn, 'plex-api-token')

        grindhouse = get_grindhouse(conn, grindhouseId)
        grindhouse_name = grindhouse[0]
        grindhouse_command = grindhouse[1]
        grindhouse_command_version = grindhouse[2]

        plex = PlexServer(baseurl, token)
        command = json.loads(grindhouse_command)

        total_videos = []
        for entry in command:
            if type(entry) is dict: # dict is anything but a trailer
                #pprint(entry)
                plex_video = plex.library.sectionByID(entry['libraryId']).get(entry['title'])
                total_videos.append(plex_video)

            if type(entry) is list: # trailers
                for trailer in entry:
                    #pprint(trailer)
                    plex_video = plex.library.sectionByID(trailer['libraryId']).get(trailer['title'])
                    total_videos.append(plex_video)

        plex.createPlaylist(grindhouse_name, total_videos)
        response = {'status':"ok", 'data': {}}
        print json.dumps(response)

if __name__ == '__main__':
    main()














#baseurl = str(sys.argv[1])
#token = str(sys.argv[2])
#baseurl = 'http://essex.int:32400'
#token = 'qf6ViozzENrUyqGo8Rdz'
#library = str(sys.argv[3])





#pprint(stuff)


# convert the keywords to json
#print json.dumps(movie['keywords'])
