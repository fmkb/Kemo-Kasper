﻿using UnityEngine;

[RequireComponent(typeof(Rigidbody2D))]

public class CellThrower : MonoBehaviour
{
    public Rigidbody2D rb;
    
    HingeJoint2D hinge;

    Vector2 velocity, lastPosition, objPosition;

    bool move = false;
    
    private GameManager gameManager;



    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        rb = GetComponent<Rigidbody2D>();
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

        // Destroy a cell that is beyod the borders
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
}