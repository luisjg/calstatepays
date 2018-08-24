import os
from os import listdir
from os.path import isfile, join

import pandas as pd
import numpy as np
import simplejson
from csuMetro_Parsing.CsvHelper import DataFrame
from csuMetro_Parsing.CsvHelper import SanitizeMajor
from csuMetro_Parsing.CsvHelper import SanitizeIndustry

from csuMetro_Parsing.JsonBuilder import JsonMajor
from csuMetro_Parsing.JsonBuilder import JsonIndustry
from csuMetro_Parsing.UniversityMajorJsonBuilder import hegisID

class IterateCsvFiles():
  
    def __init__(self):
        pass
    
    def create_hegis_code_data_frame(self,universityMajorsDataFrame):
        hegisTable = hegisID(universityMajorsDataFrame)
        hegisTable.convert_To_Json()
        
        hegisTable = hegisID(universityMajorsDataFrame)
        hegisDataFrame = hegisTable.get_hegis_codes_table_data_frame()
        hegisTable.json_output('master_hegis_category_table',hegisDataFrame)
        # print(hegisDataFrame)
    
    def master_majors_csv_to_json(self,majorsCsvFiles):
      index = 1  
      # universityMajorsList = []
      col = ["hegis_codes" ,"university_id" ,"major","id"]
      universityMajorsDataFrame = pd.DataFrame()

      for csv in majorsCsvFiles:
        fileName = csv.replace("_majors","")
        majorSanitize = SanitizeMajor(csv) # Object contains a dataFrame
        majorDataFrame = majorSanitize.sanitizeMajor() #sanitizes major

        majorPathDf,majorPathWageDf = majorSanitize.getMajorPathsDF()# get Table equiv Data Frames

        universityMajorDictionaryDf = majorSanitize.getUniversityMajorDictionaryDf() # Returns a dictionary DF
        
        jsonMajor = JsonMajor(fileName,universityMajorDictionaryDf,universityMajorsDataFrame,index) #Returns the Json
        
        majorPathDf = jsonMajor.getMajorsTables(majorPathDf)   # Sanitize majorPath Df

        index = jsonMajor.getIndex() # gets index

        jsonMajor.jsonOutput(fileName+"_majors_path",majorPathDf)
        jsonMajor.jsonOutput(fileName+"_majors_path_wages",majorPathWageDf)
        jsonMajor.jsonSanitize(fileName+"_majors_path_wages")

        universityMajorIdDf = jsonMajor.getUniversityMajorIdDf()

        universityMajorsDataFrame = universityMajorIdDf

        del majorSanitize
        del majorDataFrame
        del universityMajorDictionaryDf
        del jsonMajor
        del majorPathDf
        del majorPathWageDf
    #   print(universityMajorsDataFrame)
      self.create_hegis_code_data_frame(universityMajorsDataFrame)


    def master_industry_csv_to_json(self,industryCsvFiles):
      for csv in industryCsvFiles:
        fileName = csv.replace("_industry","")
        industrySanitize = SanitizeIndustry(csv)
        industryDataFrame = industrySanitize.sanitizeIndustry() 
        
        # get Table equiv Data Frames
        industryPathTypesDf,industryPathWagesDf,naics_titlesDf = industrySanitize.industryDF()


        # init json Industry
        jsonIndustry = JsonIndustry(fileName)
        #update Industry PathTypes
        industryPathTypesDf = jsonIndustry.getIndustryPathTypesDfTable(industryPathTypesDf)
        
        # JSon Outputs 
        jsonIndustry.jsonOutput(fileName+"_industry_path",industryPathTypesDf)
        jsonIndustry.jsonOutput(fileName+"_industry_path_wages",industryPathWagesDf)
        jsonIndustry.jsonOutput(fileName+"_naics_titles",naics_titlesDf)
        jsonIndustry.jsonSanitizeWages(fileName+"_industry_path_wages")
        jsonIndustry.jsonSanitizePath(fileName+"_industry_path")
        jsonIndustry.jsonSanitizeNaics(fileName+"_naics_titles")

        del industrySanitize
        del industryDataFrame
        del industryPathTypesDf
        del industryPathWagesDf
        del naics_titlesDf

def sort_csv_files(csvFiles):
    majorsCsvFiles = []
    industryCsvFiles = []
    for csv in csvFiles:
        if 'majors' in csv:
            majorsCsvFiles.append(csv.replace('.csv',''))
        elif 'industry' in csv:
            industryCsvFiles.append(csv.replace('.csv',''))
    return majorsCsvFiles,industryCsvFiles

def main( iterateCsvFiles = IterateCsvFiles() ):
#   able to get all csv files within working dir, 
#   sort csv's based on majors/industry
#   

    mypath = os.getcwd()
    
    csvFiles = [csvFile for csvFile in listdir(mypath) 
                 if isfile(join(mypath, csvFile)) 
                 if '.csv' in csvFile]
    
    majorsCsvFiles,industryCsvFiles = sort_csv_files(csvFiles)
    
    iterateCsvFiles.master_majors_csv_to_json(majorsCsvFiles)
    iterateCsvFiles.master_industry_csv_to_json(industryCsvFiles)
    
if __name__ == "__main__": main()
    
