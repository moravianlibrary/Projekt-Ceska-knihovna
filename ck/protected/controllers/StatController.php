<?php

class StatController extends Controller
{
	public function actionAdmin()
	{
		$this->render('admin');
	}
	
	public function actionTitles()
	{
		$dataProvider=new CArrayDataProvider(db()->createCommand("SELECT SUM(count) AS sum_count, book_id, author, title, name FROM ((({{book}} LEFT JOIN {{lib_order}} ON {{book}}.id={{lib_order}}.book_id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id) GROUP BY book_id")->queryAll(), array(
			'id'=>'titles',
			'sort'=>array(
				'defaultOrder'=>'sum_count desc',
				'attributes'=>array(
					'sum_count'=>array(
						'asc'=>'sum_count',
						'desc'=>'sum_count desc',
						),
					'title'=>array(
						'asc'=>'title',
						'desc'=>'title desc',
						),
					'author'=>array(
						'asc'=>'author',
						'desc'=>'author desc',
						),
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name desc',
						),
					),
				),
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));

		$this->render('titles',array(
			'dataProvider'=>$dataProvider,
			'page_title'=>'Books',
		));
	}
	
	public function actionLibraries()
	{
		$types = DropDownItem::items('Library.type');
		$other = $types[''];
		unset($types['']);

		$criteria=new CDbCriteria;
		if (isset($_GET['region']))
			$criteria->compare('organisation.region', $_GET['region']);
		
		if (isset($_GET['type']))
		{			
			if ($_GET['type'] == '-')
				$criteria->addNotInCondition('t.type', $types);
			else
				$criteria->compare('t.type', $_GET['type']);
		}
		
		$types['-'] = $other;
		
		$criteria->with = array('organisation');
		
		$dataProvider=new CActiveDataProvider('Library', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					'region'=>array(
						'asc'=>'organisation.region',
						'desc'=>'organisation.region desc',
						),
					),
				),
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));
		
		$this->render('libraries',array(
			'dataProvider'=>$dataProvider,
			'types'=>$types,
		));
	}
	
	public function actionPublishers()
	{
		$books = array();
		$data = array();
		foreach (db()->createCommand("SELECT * FROM ((({{pub_order}} LEFT JOIN {{book}} ON {{pub_order}}.book_id={{book}}.id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id)")->queryAll() as $rs)
		{
			$data[$rs['publisher_id']]['name'] = $rs['name'];
			$data[$rs['publisher_id']]['sum_cena'] += ($rs['count'] * $rs['price']);
			$data[$rs['publisher_id']]['sum_dod_svazky'] += $rs['delivered'];
			$data[$rs['publisher_id']]['sum_nedod_svazky'] += ($rs['count'] - $rs['delivered']);
			$data[$rs['publisher_id']]['tituly'][$rs['book_id']] += $rs['delivered'];
		}
		foreach ($data as $publisher_id=>$publisher)
		{
			foreach ($publisher['tituly'] as $book_id=>$delivered)
			{
				if (!array_key_exists('sum_dod_tituly', $publisher))
					$data[$publisher_id]['sum_dod_tituly'] = $data[$publisher_id]['sum_nedod_tituly'] = 0;
				if ($delivered)
					$data[$publisher_id]['sum_dod_tituly']++;
				else
					$data[$publisher_id]['sum_nedod_tituly']++;					
			}
		}
		
		$dataProvider=new CArrayDataProvider($data, array(
			'id'=>'publishers',
			'sort'=>array(
				'defaultOrder'=>'sum_dod_tituly desc',
				'attributes'=>array(
					'sum_dod_tituly'=>array(
						'asc'=>'sum_dod_tituly',
						'desc'=>'sum_dod_tituly desc',
						),
					'sum_nedod_tituly'=>array(
						'asc'=>'sum_nedod_tituly',
						'desc'=>'sum_nedod_tituly desc',
						),
					'sum_dod_svazky'=>array(
						'asc'=>'sum_dod_svazky',
						'desc'=>'sum_dod_svazky desc',
						),
					'sum_nedod_svazky'=>array(
						'asc'=>'sum_nedod_svazky',
						'desc'=>'sum_nedod_svazky desc',
						),
					'sum_cena'=>array(
						'asc'=>'sum_cena',
						'desc'=>'sum_cena desc',
						),
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name desc',
						),
					),
				),
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));

		$this->render('publishers',array(
			'dataProvider'=>$dataProvider,
		));		
	}
	
	public function actionTitlesNotDelivered()
	{
		$dataProvider=new CArrayDataProvider(db()->createCommand("SELECT SUM(count-delivered) as sum_count, title, author, name FROM ((({{pub_order}} LEFT JOIN {{book}} ON {{pub_order}}.book_id={{book}}.id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id) WHERE count!=delivered GROUP BY book_id")->queryAll(), array(
			'id'=>'pub_not_delivered',
			'sort'=>array(
				'defaultOrder'=>'sum_count desc',
				'attributes'=>array(
					'sum_count'=>array(
						'asc'=>'sum_count',
						'desc'=>'sum_count desc',
						),
					'title'=>array(
						'asc'=>'title',
						'desc'=>'title desc',
						),
					'author'=>array(
						'asc'=>'author',
						'desc'=>'author desc',
						),
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name desc',
						),
					),
				),
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));

		$this->render('titles',array(
			'dataProvider'=>$dataProvider,
			'page_title'=>'Undelivered Titles',
		));
	}

	public function actionTitleChanges()
	{
		$dataProvider=new CActiveDataProvider(Book::model()->with(array('publisher', 'publisher.organisation', 'book_titles'=>array('joinType'=>'INNER JOIN')))->together(), array(
			'sort'=>array(
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name desc',
					),
				),
			),
			'pagination'=>array(
				'pageSize'=>1000,
			),
		));
		
		$this->render('titleChanges',array(
			'dataProvider'=>$dataProvider,
		));		
	}
}