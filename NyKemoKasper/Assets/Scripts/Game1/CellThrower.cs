using System.Collections;
using System.Collections.Generic;
using UnityEngine;

[RequireComponent(typeof(Rigidbody2D))]

public class CellThrower : MonoBehaviour
{
    public Rigidbody2D rb;
    
    HingeJoint2D hinge;

    Vector2 velocity;
    Vector2 lastPosition;
    Vector2 objPosition;

    bool move = false;

    public GameObject pointsBonusPrefab;
    private GameObject pointsParent;
    private List<GameObject> points;

    private GameManager gameManager;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        rb = GetComponent<Rigidbody2D>();
        points = new List<GameObject>();
        pointsParent = GameObject.Find("Points");
    }

    private void OnMouseDown()
    {
        //Saves the mouse position to screen coordinates
        Vector2 mousePosition = new Vector2(Input.mousePosition.x, Input.mousePosition.y);
        transform.position = new Vector3(Camera.main.ScreenToWorldPoint(mousePosition).x, Camera.main.ScreenToWorldPoint(mousePosition).y, transform.position.z);

        //Saves the HingeJoint2D component to variable that we can use
        hinge = GetComponent(typeof(HingeJoint2D)) as HingeJoint2D;
        //A Boolean that indicates we can start calculating movement
        move = true;
        //Reenables the hinge which is likely disabled after OnMouseUp is used
        hinge.enabled = true;
        //Assigns whatever rigidbody we have clicked on to our hinge
        hinge.connectedBody = rb;
        //Prevents the hinge from adjusting the anchorpoint during fixed update, this well be set to true in OnMouseUp
        hinge.autoConfigureConnectedAnchor = false;

    }

    private void FixedUpdate()
    {
        if (move == true)
        {
            //Saves the mouse position to screen coordinates
            Vector2 mousePosition = new Vector2(Input.mousePosition.x, Input.mousePosition.y);
                transform.position = new Vector3(Camera.main.ScreenToWorldPoint(mousePosition).x, Camera.main.ScreenToWorldPoint(mousePosition).y, transform.position.z);
            
                //-----Continuously calculates the velocity to apply to the object after it has been thrown-----
                //Takes the current object postition to use for the velocity calculation
                objPosition = transform.position;
                //Calculates velocity
                velocity = (objPosition - lastPosition) / (Time.fixedDeltaTime);
                //Saves the current position for use in the next velocity calculation
                lastPosition = transform.position;
        }

        if (transform.position.x < -1030 || transform.position.y < -600 || transform.position.x > 1030 || transform.position.y > 600)
        {
            Destroy(this.gameObject);
        }
    }

    private void OnMouseUp()
    {
        //Removes the hinges influence from the rigidbody
        hinge.connectedBody = null;
        //Allows the Anchor point to be moved for when we click on a new rigid body
        hinge.autoConfigureConnectedAnchor = true;
        //Disables the rigid body
        hinge.enabled = false;
        //Stops the calculations in FixedUpdate
        move = false;
        //Combines the velocity from the mouse with the velocity from the hinge and applies it to the desired rigid body
        rb.velocity = (rb.velocity + velocity)/2;
    }

    private void OnTriggerEnter2D(Collider2D other)
    {
        if(other.name == "Pipe")
        {
            GetComponent<Animator>().Play("OtherCellExplode");
            Destroy(this, 1 / 6f);
            if(this.tag == "BonusCell")
            {
                ShowPoints();
            }
        }
    }

    private void ShowPoints()
    {
        Vector3 position = new Vector3(transform.position.x, transform.position.y, transform.position.z);
        GameObject point = null;
        point = Instantiate(pointsBonusPrefab, position, Quaternion.identity);
        point.transform.GetChild(0).GetChild(0).GetComponent<TextMesh>().text = "" + 100;
        points.Add(point);
        point.transform.SetParent(pointsParent.transform);
        gameManager.AddPointsForBonusCell();
        Destroy(point, 1.15f);
    }
}