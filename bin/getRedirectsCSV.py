import json
import csv
from argparse import ArgumentParser

parser = ArgumentParser(description="Convert file with JSON redirects to CSV")

parser.add_argument("-f", "--file", dest="json_file",
                    help="Path to JSON file from which to read redirects",
                    metavar="JSON Filename", required=True)
parser.add_argument("-o", "--output-file", dest="csv_file",
                    help="Name of output file (specify exactly with .csv!!!)",
                    metavar="CSV Filename", required=True)


args = parser.parse_args()

with open(args.json_file) as json_file:
    data = json.load(json_file)
    redirects = data['redirects']

    # open the file in the write mode
    with open(args.csv_file, 'w', newline='') as csv_file:
        # create the csv writer
        writer = csv.writer(csv_file)
        for r in redirects:
            row = [r['path'], r['target']['target']]
            print(r)
            print('Importing redirect from ' + r['path'] + ' to ' + r['target']['target'])
            # write a row to the csv file
            writer.writerow(row)
