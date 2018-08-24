import pandas as pd
import numpy as np
import json
import simplejson


import simplejson
import json

class hegisID:
  def __init__(self,df):
    self.df = df

  def head(self):
    print(self.df.head())

  def convert_To_Json(self):
    self.df['hegis_codes'] = (self.df['hegis_codes']).astype(int)
    self.df['id'] = (self.df['hegis_codes']).astype(int)
    self.df['university_id'] = (self.df['hegis_codes']).astype(int)

    self.json_output('master_university_table',self.df)

  def get_hegis_codes_table_data_frame(self):
    hegisCodesTableDataFrame = self.df.loc[:,['id','hegis_codes','major']]
    hegisCodesTableDataFrame['hegis_category_id'] = -1

    for index,row in hegisCodesTableDataFrame.iterrows():
      hegis = (str)((int)(row[1]))
      lengthOfHegis = len(hegis)

      hegisCategoryId = '00'
      if ( lengthOfHegis == 4 ) :
        hegisCategoryId = '0'+hegis[0]
      elif ( lengthOfHegis == 5 ):
        hegisCategoryId = hegis[0:2]

      hegisCategoryId = ( int )( hegisCategoryId )
      hegisCodesTableDataFrame.ix[index,'hegis_category_id'] = hegisCategoryId
    
    return hegisCodesTableDataFrame
  
  def json_output(self,fileName, df):
    
    output = df.to_dict(orient='record')

    with open ('../../database/data/'+fileName+'.json', 'w' ) as fp:
      fp.write(simplejson.dumps(output, sort_keys=False, indent=4, separators=(',', ': '), ensure_ascii=False,ignore_nan=True))
    fp.close()
    
    
